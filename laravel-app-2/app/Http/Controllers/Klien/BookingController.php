<?php

namespace App\Http\Controllers\Klien;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\FeeReference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Dashboard / Riwayat Booking Klien
     */
    public function index()
    {
        $bookings = Booking::where('client_id', Auth::id())->latest()->get();
        return view('klien.dashboard', compact('bookings'));
    }

    /**
     * Form Pendaftaran Booking Baru (Self-Service) – Dynamic dari ServiceCatalog
     */
    public function create()
    {
        $catalogs = \App\Models\ServiceCatalog::where('is_active', true)
                        ->orderBy('sort_order')->orderBy('id')->get();
        return view('klien.bookings.create', compact('catalogs'));
    }

    /**
     * Simpan Booking Baru
     */
    public function store(Request $request)
    {
        // Validasi catalog ID dari DB (aman dari manipulasi)
        $catalog = \App\Models\ServiceCatalog::where('is_active', true)->find($request->service_catalog_id);
        if (!$catalog) {
            return back()->withErrors(['service_catalog_id' => 'Paket yang dipilih tidak valid.'])->withInput();
        }

        $request->validate([
            'service_catalog_id' => 'required|exists:service_catalogs,id',
            'event_date'   => [
                'required',
                'date',
                'after:today',
                function ($attribute, $value, $fail) {
                    $exists = Booking::where('event_date', $value)
                        ->whereIn('status', ['dp_paid', 'confirmed', 'paid_full', 'completed'])
                        ->exists();
                    if ($exists) {
                        $fail('Tanggal ' . \Carbon\Carbon::parse($value)->format('d M Y') . ' sudah penuh/di-booking. Silakan pilih tanggal lain.');
                    }
                },
            ],
            'event_start'       => 'required',
            'event_end'         => 'required',
            'venue'             => 'required|string',
            'venue_address'     => 'required|string|min:10',
            'client_phone'      => 'required|string',
            'latitude'          => 'nullable|numeric|between:-90,90',
            'longitude'         => 'nullable|numeric|between:-180,180',
        ]);

        try {
            $booking = \Illuminate\Support\Facades\DB::transaction(function () use ($request, $catalog) {

                // FIX A-02: Cek ketersediaan tanggal DALAM transaksi dengan lockForUpdate
                // Ini memastikan tidak ada booking lain yang bisa masuk untuk tanggal ini
                // sebelum insert kita selesai (Pessimistic Locking).
                $conflict = \App\Models\Booking::where('event_date', $request->event_date)
                    ->whereIn('status', ['dp_paid', 'confirmed', 'paid_full', 'completed'])
                    ->lockForUpdate()
                    ->exists();

                if ($conflict) {
                    throw new \Exception('Tanggal ' . \Carbon\Carbon::parse($request->event_date)->format('d M Y') . ' sudah penuh/di-booking. Silakan pilih tanggal lain.');
                }

                // Harga dari catalog di server — immune dari manipulasi
                $basePrice = $catalog->price;
                $dpAmount  = $basePrice * 0.50;

                return \App\Models\Booking::create([
                    'client_id'          => Auth::id(),
                    'client_name'        => Auth::user()->name,
                    'client_phone'       => $request->client_phone,
                    'event_type'         => $catalog->name,
                    'service_catalog_id' => $catalog->id,
                    'event_date'         => $request->event_date,
                    'event_start'        => $request->event_start,
                    'event_end'          => $request->event_end,
                    'venue'              => $request->venue,
                    'venue_address'      => $request->venue_address,
                    'latitude'           => $request->latitude,
                    'longitude'          => $request->longitude,
                    'booking_source'     => 'web',
                    'status'             => 'pending',
                    'total_price'        => $basePrice,
                    'dp_amount'          => $dpAmount,
                ]);
            });

            // Kirim notifikasi ke Admin
            $admins = \App\Models\User::where('role', 'admin')->get();
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewBookingCreated($booking));

            return redirect()->route('klien.bookings.show', $booking->id)
                ->with('success', 'Booking berhasil diajukan! Tim kami akan meninjau pesanan Anda.');

        } catch (\Exception $e) {
            return back()->withErrors(['event_date' => $e->getMessage()])->withInput();
        }
    }

    /**
     * Negotiation Hub (Status Booking & Harga)
     */
    public function show($id)
    {
        $booking = Booking::where('id', $id)->where('client_id', Auth::id())->firstOrFail();
        return view('klien.bookings.show', compact('booking'));
    }

    /**
     * Upload Bukti Transportasi Pembayaran
     */
    public function uploadProof(Request $request, $id)
    {
        $booking = Booking::where('id', $id)->where('client_id', Auth::id())->firstOrFail();

        $request->validate([
            // FIX B-04: Tambahkan validasi mimetypes berbasis finfo untuk mencegah bypass ekstensi
            'payment_proof' => 'required|image|mimetypes:image/jpeg,image/png,image/jpg|max:5120',
        ]);

        // Simpan file ke storage public (Contoh sederhana)
        $path = $request->file('payment_proof')->store('proofs', 'public');

        $booking->update([
            'payment_proof' => $path
        ]);

        // Kirim notifikasi ke Admin
        $admins = \App\Models\User::where('role', 'admin')->get();
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\DpPaymentProofUploaded($booking));

        return redirect()->back()->with('success', 'Bukti bayar berhasil diunggah! Menunggu konfirmasi Admin.');
    }

    /**
     * Upload Bukti Pelunasan
     */
    public function uploadFullProof(Request $request, $id)
    {
        $booking = Booking::where('id', $id)->where('client_id', Auth::id())->firstOrFail();

        if (!in_array($booking->status, ['dp_paid', 'confirmed'])) {
            return redirect()->back()->with('error', 'Status pesanan belum valid untuk pelunasan.');
        }

        $request->validate([
            // FIX B-04: Tambahkan validasi mimetypes berbasis finfo
            'full_payment_proof' => 'required|image|mimetypes:image/jpeg,image/png,image/jpg|max:5120',
        ]);

        $path = $request->file('full_payment_proof')->store('proofs', 'public');

        $booking->update([
            'full_payment_proof' => $path
        ]);

        // Kirim notifikasi ke Admin
        $admins = \App\Models\User::where('role', 'admin')->get();
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\FullPaymentProofUploaded($booking));

        return redirect()->back()->with('success', 'Bukti pelunasan berhasil diunggah! Menunggu verifikasi dari Admin.');
    }

    /**
     * Helper default penalty tiers
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
     * Helper penalty tiers dari SiteContent atau default
     */
    private function getPenaltyTiers(): array
    {
        $raw = \App\Models\SiteContent::where('key', 'penalty_tiers')->value('value');
        if ($raw) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded) && count($decoded) > 0) {
                return $decoded;
            }
        }
        return $this->getDefaultTiers();
    }

    /**
     * Kalkulasi denda secara PHP
     */
    private function calculatePenalty(int $daysBefore, float $totalPrice, array $tiers): float
    {
        usort($tiers, fn($a, $b) => $b['days_from'] <=> $a['days_from']);

        foreach ($tiers as $tier) {
            if ($daysBefore >= $tier['days_from']) {
                return $totalPrice * ($tier['percentage'] / 100);
            }
        }

        return $totalPrice * ($tiers[array_key_last($tiers)]['percentage'] / 100);
    }

    /**
     * Memproses pembatalan mandiri oleh Klien
     */
    public function cancel(Request $request, $id)
    {
        $booking = Booking::where('id', $id)->where('client_id', Auth::id())->firstOrFail();

        $request->validate([
            'reason'                 => 'required|string',
            'digital_acknowledgement'=> 'required|boolean|accepted',
        ]);

        if ($booking->status === 'cancelled') {
            return redirect()->back()->with('error', 'Pesanan sudah dibatalkan sebelumnya.');
        }

        if (!in_array($booking->status, ['pending', 'dp_paid'])) {
            return redirect()->back()->with('error', 'Status pesanan tidak valid untuk pembatalan mandiri. Silakan hubungi Admin sanggar.');
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($booking, $request) {
                $cancelDate = Carbon::now()->format('Y-m-d');
                $eventDate  = is_string($booking->event_date)
                    ? $booking->event_date
                    : $booking->event_date->format('Y-m-d');

                $daysBefore = max(0, Carbon::parse($eventDate)->diffInDays(Carbon::parse($cancelDate), false));

                // Kalkulasi penalti: Coba SQL Function terlebih dahulu, jika gagal fallback ke PHP
                try {
                    $query = \Illuminate\Support\Facades\DB::select('SELECT fn_calculate_cancellation_penalty(?, ?, ?) AS penalty_amount', [
                        $eventDate, $cancelDate, $booking->total_price
                    ]);
                    $penaltyAmount = $query[0]->penalty_amount ?? 0;
                } catch (\Exception $sqlEx) {
                    $tiers = $this->getPenaltyTiers();
                    $penaltyAmount = $this->calculatePenalty($daysBefore, (float) $booking->total_price, $tiers);
                }

                $penaltyPct   = ($booking->total_price > 0) ? ($penaltyAmount / $booking->total_price) * 100 : 0;
                $refundAmount = max(0, $booking->dp_amount - $penaltyAmount);

                \App\Models\Cancellation::create([
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

                $booking->update(['status' => 'cancelled']);
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
                }
            });

            // Kirim notifikasi ke Admin bahwa klien melakukan pembatalan
            $admins = \App\Models\User::where('role', 'admin')->get();
            try {
                \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\BookingStatusChanged($booking, 'telah dibatalkan oleh klien ' . Auth::user()->name));
            } catch (\Exception $notifEx) {
                // Jangan menggagalkan transaksi jika notifikasi error
            }

            return redirect()->route('klien.bookings.show', $booking->id)
                ->with('success', 'Pesanan Anda berhasil dibatalkan. Pengembalian DP (jika ada) sedang diproses oleh admin.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pembatalan: ' . $e->getMessage());
        }
    }
}
