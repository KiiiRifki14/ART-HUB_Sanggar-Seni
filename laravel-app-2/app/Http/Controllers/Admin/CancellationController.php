<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Cancellation;
use App\Models\SiteContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CancellationController extends Controller
{
    /**
     * Default penalty tiers (fallback jika belum diatur admin)
     */
    private function getDefaultTiers(): array
    {
        return [
            ['days_from' => 14, 'percentage' => 10,  'label' => '≥ H-14'],
            ['days_from' => 7,  'percentage' => 30,  'label' => 'H-7 s/d H-13'],
            ['days_from' => 3,  'percentage' => 50,  'label' => 'H-3 s/d H-6'],
            ['days_from' => 0,  'percentage' => 75,  'label' => '< H-3'],
        ];
    }

    /**
     * Ambil penalty tiers dari SiteContent atau gunakan default
     */
    private function getPenaltyTiers(): array
    {
        $raw = SiteContent::where('key', 'penalty_tiers')->value('value');
        if ($raw) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded) && count($decoded) > 0) {
                return $decoded;
            }
        }
        return $this->getDefaultTiers();
    }

    /**
     * Hitung penalti berdasarkan tiers yang tersimpan
     */
    private function calculatePenalty(int $daysBefore, float $totalPrice, array $tiers): float
    {
        // Urutkan dari tertinggi ke terendah (ambil tier yang paling cocok)
        usort($tiers, fn($a, $b) => $b['days_from'] <=> $a['days_from']);

        foreach ($tiers as $tier) {
            if ($daysBefore >= $tier['days_from']) {
                return $totalPrice * ($tier['percentage'] / 100);
            }
        }

        // Fallback: tier terendah
        return $totalPrice * ($tiers[array_key_last($tiers)]['percentage'] / 100);
    }

    /**
     * Daftar seluruh pembatalan
     */
    public function index()
    {
        $pending = Cancellation::where('status', 'pending')->count();
        $totalPenalty = Cancellation::sum('penalty_amount');
        $totalRefund = Cancellation::sum('refund_amount');
        $totalCancellations = Cancellation::count();

        $cancellations = Cancellation::with('booking')->latest()->paginate(10);
        $penaltyTiers  = $this->getPenaltyTiers();

        return view('admin.cancellations.index', compact(
            'cancellations',
            'penaltyTiers',
            'pending',
            'totalPenalty',
            'totalRefund',
            'totalCancellations'
        ));
    }

    /**
     * Simpan pengaturan formula penalti
     */
    public function updatePenaltySettings(Request $request)
    {
        $request->validate([
            'tiers'                  => 'required|array|min:1|max:8',
            'tiers.*.days_from'      => 'required|integer|min:0',
            'tiers.*.percentage'     => 'required|numeric|min:0|max:100',
        ], [
            'tiers.required'             => 'Minimal satu tier penalti harus diisi.',
            'tiers.*.days_from.required' => 'H-Hari wajib diisi.',
            'tiers.*.percentage.required'=> 'Persentase penalti wajib diisi.',
            'tiers.*.percentage.max'     => 'Persentase tidak boleh melebihi 100%.',
        ]);

        $tiers = collect($request->tiers)->map(function ($tier) {
            return [
                'days_from'  => (int)   $tier['days_from'],
                'percentage' => (float) $tier['percentage'],
                'label'      => trim($tier['label'] ?? ''),
            ];
        })->values()->toArray();

        SiteContent::updateOrCreate(
            ['key' => 'penalty_tiers'],
            ['value' => json_encode($tiers)]
        );

        return redirect()->route('admin.cancellations.index')
            ->with('success', 'Formula penalti berhasil diperbarui! Akan berlaku untuk pembatalan berikutnya.');
    }

    /**
     * Memproses Pembatalan Event oleh Klien
     * Menggunakan formula penalti dari SiteContent (bisa diubah admin)
     */
    public function store(Request $request, Booking $booking)
    {
        $request->validate([
            'reason'                 => 'required|string',
            'digital_acknowledgement'=> 'required|boolean|accepted',
        ]);

        if ($booking->status === 'cancelled') {
            return redirect()->back()->with('error', 'Booking sudah dibatalkan sebelumnya.');
        }

        try {
            DB::transaction(function () use ($booking, $request) {
                $eventDateStr = is_string($booking->event_date)
                    ? $booking->event_date
                    : $booking->event_date->format('Y-m-d');
                $eventStartStr = $booking->event_start instanceof \Carbon\Carbon
                    ? $booking->event_start->format('H:i:s')
                    : (is_string($booking->event_start) ? $booking->event_start : '00:00:00');

                $eventDateTime = Carbon::parse($eventDateStr . ' ' . $eventStartStr);
                $hoursBefore = Carbon::now()->diffInHours($eventDateTime, false);
                $daysBefore = (int) ceil($hoursBefore / 24);
                if ($daysBefore < 0) {
                    $daysBefore = 0;
                }

                $tiers = $this->getPenaltyTiers();
                $penaltyAmount = $this->calculatePenalty($daysBefore, (float) $booking->total_price, $tiers);

                $penaltyPct   = ($booking->total_price > 0) ? ($penaltyAmount / $booking->total_price) * 100 : 0;
                $refundAmount = max(0, $booking->dp_amount - $penaltyAmount);

                Cancellation::create([
                    'booking_id'              => $booking->id,
                    'cancellation_date'       => Carbon::now()->format('Y-m-d'),
                    'days_before_event'       => $daysBefore,
                    'penalty_percentage'      => $penaltyPct,
                    'penalty_amount'          => $penaltyAmount,
                    'refund_amount'           => $refundAmount,
                    'status'                  => 'pending',
                    'reason'                  => $request->reason,
                    'digital_acknowledgement' => $request->digital_acknowledgement,
                    'acknowledged_ip'         => $request->ip(),
                    'acknowledged_at'         => now(),
                    'acknowledged_ua'         => $request->userAgent(),
                ]);
            });

            return redirect()->back()->with('success', 'Permohonan pembatalan berhasil diajukan dan berstatus Pending. Silakan lakukan aksi Setujui atau Tolak pada Dashboard Admin.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pembatalan: ' . $e->getMessage());
        }
    }

    /**
     * Setujui Permohonan Pembatalan (Admin Side)
     */
    public function approveCancellation(Cancellation $cancellation)
    {
        if ($cancellation->status !== 'pending') {
            return redirect()->back()->with('error', 'Permohonan pembatalan ini sudah diproses sebelumnya.');
        }

        try {
            DB::transaction(function () use ($cancellation) {
                // 1. Update status cancellation menjadi 'processed' (Approved)
                $cancellation->update(['status' => 'processed']);

                // 2. Update status booking menjadi 'cancelled'
                $booking = $cancellation->booking;
                $booking->update(['status' => 'cancelled']);

                // 3. Cari atau buat Event dengan status 'cancelled'
                if ($booking->event) {
                    $event = $booking->event;
                    $event->update(['status' => 'cancelled']);
                    
                    // Detach personnel
                    $event->personnel()->detach();

                    // Hapus pemakaian kostum sanggar
                    \App\Models\CostumeUsage::where('event_id', $event->id)->delete();

                    // Hapus sewaan kostum vendor
                    \App\Models\CostumeRental::where('event_id', $event->id)->delete();

                    // Sesuaikan operational costs dan financial record
                    $financialRecord = \App\Models\FinancialRecord::where('event_id', $event->id)->first();
                    if ($financialRecord) {
                        \App\Models\OperationalCost::where('financial_record_id', $financialRecord->id)
                            ->where('category', 'sewa_kostum')
                            ->delete();

                        $totalActual = \App\Models\OperationalCost::where('financial_record_id', $financialRecord->id)->sum('actual_amount');
                        $financialRecord->update(['actual_operational_cost' => $totalActual]);
                    }
                } else {
                    $baseCode = 'EVT-' . date('Y') . '-' . str_pad($booking->id, 3, '0', STR_PAD_LEFT);
                    $eventCode = $baseCode;
                    $counter = 1;
                    while (\App\Models\Event::where('event_code', $eventCode)->exists()) {
                        $eventCode = $baseCode . '-' . $counter;
                        $counter++;
                    }

                    $event = \App\Models\Event::create([
                        'booking_id'      => $booking->id,
                        'event_code'      => $eventCode,
                        'status'          => 'cancelled',
                        'event_date'      => $booking->event_date,
                        'event_start'     => $booking->event_start,
                        'event_end'       => $booking->event_end,
                        'venue'           => $booking->venue,
                        'personnel_count' => 0,
                    ]);
                }

                // 4. Input baris baru / update financial_records sebagai denda sanggar
                \App\Models\FinancialRecord::updateOrCreate(
                    ['event_id' => $event->id],
                    [
                        'total_revenue'        => $cancellation->penalty_amount,
                        'fixed_profit'         => $cancellation->penalty_amount,
                        'dp_received'          => $booking->dp_amount,
                        'operational_budget'   => 0,
                        'safety_buffer_amt'    => 0,
                        'profit_locked'        => true,
                        'status'               => 'locked',
                    ]
                );
            });

            return redirect()->back()->with('success', 'Permohonan pembatalan berhasil disetujui. Booking telah resmi dibatalkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyetujui pembatalan: ' . $e->getMessage());
        }
    }

    /**
     * Tolak Permohonan Pembatalan (Admin Side)
     */
    public function rejectCancellation(Cancellation $cancellation)
    {
        if ($cancellation->status !== 'pending') {
            return redirect()->back()->with('error', 'Permohonan pembatalan ini sudah diproses sebelumnya.');
        }

        try {
            DB::transaction(function () use ($cancellation) {
                // Update status cancellation menjadi 'refunded' (Rejected)
                $cancellation->update(['status' => 'refunded']);
            });

            return redirect()->back()->with('success', 'Permohonan pembatalan berhasil ditolak. Jadwal pementasan tetap berjalan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menolak pembatalan: ' . $e->getMessage());
        }
    }
}
