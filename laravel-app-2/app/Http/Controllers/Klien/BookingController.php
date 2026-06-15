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
        $clientId = Auth::id();
        $bookings = Booking::where('client_id', $clientId)->latest()->paginate(10);
        
        $aktif   = Booking::where('client_id', $clientId)->whereIn('status', ['pending', 'dp_paid', 'confirmed'])->count();
        $selesai = Booking::where('client_id', $clientId)->where('status', 'completed')->count();
        $total   = Booking::where('client_id', $clientId)->count();

        return view('klien.dashboard', compact('bookings', 'aktif', 'selesai', 'total'));
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
                'after_or_equal:' . now()->addDays(30)->toDateString(),
            ],
            'event_start'       => 'required',
            'event_end'         => 'required',
            'venue'             => 'required|string',
            'venue_address'     => 'required|string|min:10',
            'client_phone'      => 'required|string',
            'latitude'          => 'nullable|numeric|between:-90,90',
            'longitude'         => 'nullable|numeric|between:-180,180',
        ], [
            'event_date.after_or_equal' => 'Tanggal pementasan minimal harus H+30 dari hari ini.',
        ]);

        try {
            $booking = \Illuminate\Support\Facades\DB::transaction(function () use ($request, $catalog) {


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

        if (!$booking->is_admin_confirmed) {
            return redirect()->back()->with('error', 'Pemesanan Anda masih direview oleh admin. Anda belum bisa mengunggah bukti pembayaran.');
        }

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

                if ($booking->status === 'pending') {
                    $penaltyAmount = 0;
                    $penaltyPct    = 0;
                    $refundAmount  = 0;
                } else {
                    $tiers = $this->getPenaltyTiers();
                    $penaltyAmount = $this->calculatePenalty($daysBefore, (float) $booking->total_price, $tiers);
                    $penaltyPct   = ($booking->total_price > 0) ? ($penaltyAmount / $booking->total_price) * 100 : 0;
                    $refundAmount = max(0, $booking->dp_amount - $penaltyAmount);
                }

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

                // Hapus/lepaskan status bookings dan logistik ditunda hingga Admin menyetujui pembatalan
            });

            // Kirim notifikasi ke Admin bahwa klien mengajukan pembatalan
            $admins = \App\Models\User::where('role', 'admin')->get();
            try {
                \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\BookingStatusChanged($booking, 'mengajukan permohonan pembatalan (menunggu persetujuan admin).'));
            } catch (\Exception $notifEx) {
                // Jangan menggagalkan transaksi jika notifikasi error
            }

            return redirect()->route('klien.bookings.show', $booking->id)
                ->with('success', 'Permohonan pembatalan pesanan Anda berhasil diajukan dan sedang menunggu persetujuan dari Admin. Denda dihitung sesuai formula yang berlaku.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pembatalan: ' . $e->getMessage());
        }
    }
}
