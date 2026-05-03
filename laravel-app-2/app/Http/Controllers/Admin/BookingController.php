<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Event;
use App\Models\FinancialRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Menampilkan daftar semua booking
     */
    public function index()
    {
        $bookings = Booking::with('client')
            ->latest()
            ->get();
        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Menampilkan detail satu booking
     */
    public function show(Booking $booking)
    {
        $booking->load(['client', 'event.financialRecord']);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * DP VERIFICATION: Inbox mandiri untuk validasi bukti DP
     * Versi baru dengan Summary Stats (Antrean, Total DP Masuk, Profit Terkunci)
     */
    public function dpVerification()
    {
        // Booking pending dengan bukti bayar sudah di-upload (menunggu konfirmasi Admin)
        $pendingWithProof = Booking::with('client')
            ->where('status', 'pending')
            ->whereNotNull('payment_proof')
            ->latest()
            ->get();

        // Booking pending yang belum ada bukti bayar (menunggu Klien)
        $pendingNoProof = Booking::with('client')
            ->where('status', 'pending')
            ->whereNull('payment_proof')
            ->latest()
            ->get();

        // ── SUMMARY CARD 1: Jumlah antrean menunggu verifikasi
        $antreanCount = $pendingWithProof->count();

        // ── SUMMARY CARD 2: Total DP yang sudah masuk & dikonfirmasi
        $totalDpMasuk = Booking::whereIn('status', ['dp_paid', 'paid_full', 'completed'])
            ->sum('dp_amount');

        // ── SUMMARY CARD 3: Total Profit yang sudah terkunci di financial_records
        $totalProfitLocked = FinancialRecord::where('profit_locked', true)
            ->sum('fixed_profit');

        return view('admin.bookings.dp-verification', compact(
            'pendingWithProof',
            'pendingNoProof',
            'antreanCount',
            'totalDpMasuk',
            'totalProfitLocked'
        ));
    }

    /**
     * REJECT PROOF: Menghapus bukti bayar agar klien bisa re-upload
     * Status booking tetap 'pending', file fisik & path database dihapus
     */
    public function rejectProof(Request $request, Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya booking berstatus pending yang bisa ditolak buktinya.');
        }

        // Hapus file fisik dari disk storage jika ada
        if ($booking->payment_proof && Storage::disk('public')->exists($booking->payment_proof)) {
            Storage::disk('public')->delete($booking->payment_proof);
        }

        // Kosongkan kolom payment_proof — status tetap 'pending' agar klien bisa re-upload
        $booking->update([
            'payment_proof' => null,
            'payment_receipt' => null,
        ]);

        return redirect()->back()->with('warning',
            '⚠️ Bukti Transfer dari ' . $booking->client_name . ' telah DITOLAK dan dihapus. Klien dapat melakukan upload ulang.');
    }

    /**
     * Konfirmasi Pelunasan Penuh (100%)
     */
    public function confirmFullPayment(Request $request, Booking $booking)
    {
        if (!in_array($booking->status, ['dp_paid', 'confirmed'])) {
            return redirect()->back()->with('error', 'Status booking tidak valid untuk pelunasan.');
        }

        $booking->update([
            'status'       => 'paid_full',
            'full_paid_at' => now(),
        ]);

        // Update status event terkait jika ada
        if ($booking->event) {
            $booking->event->update(['status' => 'ready']);
        }

        return redirect()->back()->with('success', 'Pelunasan 100% berhasil dikonfirmasi! Status booking: PAID (Lunas).');
    }

    /**
     * Update harga total saat negosiasi
     */
    public function updatePrice(Request $request, Booking $booking)
    {
        $request->validate([
            'total_price' => 'required|numeric|min:0',
        ]);

        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya booking dengan status pending yang bisa diubah harganya.');
        }

        $booking->update([
            'total_price' => $request->total_price,
            // Re-adjust DP minimum based on new total
            'dp_amount' => $request->total_price * 0.50,
        ]);

        return redirect()->back()->with('success', 'Harga akhir kontrak (Nego) berhasil diupdate menjadi Rp ' . number_format($request->total_price, 0, ',', '.'));
    }

    /**
     * MENGONFIRMASI PEMBAYARAN DP DAN MENGUNCI LABA
     * Sesuai standar materi Basis Data 2 (SQL Transaction).
     */
    public function confirmPayment(Request $request, Booking $booking)
    {
        // Validasi state agar tidak double confirm
        if ($booking->status === 'dp_paid' || $booking->status === 'confirmed') {
            return redirect()->back()->with('error', 'Booking ini sudah dikonfirmasi sebelumnya.');
        }

        // Validasi input: Admin WAJIB mengisi nominal fixed profit secara manual
        $request->validate([
            'fixed_profit_nominal' => 'required|numeric|min:0',
        ], [
            'fixed_profit_nominal.required' => 'Nominal Fixed Profit wajib diisi oleh Admin sebelum mengunci laba.',
        ]);

        try {
            // Variabel yang perlu dibaca di luar closure transaction
            $profitStatus = 'locked';
            $targetProfit = 0;

            DB::transaction(function () use ($booking, $request, &$profitStatus, &$targetProfit) {
                
                // 1. UPDATE STATUS BOOKING & BUKTI TRANSFER
                $receiptPath = $request->input('receipt_path', $booking->payment_receipt); 

                $booking->update([
                    'status' => 'dp_paid',
                    'payment_receipt' => $receiptPath,
                    'dp_paid_at' => now(),
                ]);

                // 2. BUAT ENTRI EVENT OTOMATIS
                $eventCode = 'EVT-' . date('Y') . '-' . str_pad($booking->id, 3, '0', STR_PAD_LEFT);
                
                $event = Event::create([
                    'booking_id'      => $booking->id,
                    'event_code'      => $eventCode,
                    'status'          => 'planning',
                    'event_date'      => $booking->event_date,
                    'event_start'     => $booking->event_start,
                    'event_end'       => $booking->event_end,
                    'venue'           => $booking->venue,
                    'personnel_count' => 12,
                ]);

                // 3. KUNCI FIXED PROFIT — INPUT MANUAL DARI ADMIN (bukan otomatis 30%)
                // ══════════════════════════════════════════════════════════════
                // Pimpinan Sanggar menentukan nominal keuntungan langsung (misal: Rp 2.500.000)
                // bukan persentase baku, sesuai hasil wawancara.
                // ══════════════════════════════════════════════════════════════
                $targetProfit = (float) $request->input('fixed_profit_nominal');
                // Hitung persen informasional (untuk dicatat di DB, bukan untuk kalkulasi)
                $profitPct    = $booking->total_price > 0 ? ($targetProfit / $booking->total_price) * 100 : 0;
                $isOverridden = true; // Selalu true karena manual
                $dpMasuk      = $booking->dp_amount;                         // e.g. Rp 1.5 Jt (DP VIP kecil)

                if ($dpMasuk >= $targetProfit) {
                    // SKENARIO NORMAL: DP cukup besar — potong laba di depan
                    $fixedProfit       = $targetProfit;
                    $operationalBudget = $dpMasuk - $fixedProfit;
                    $profitStatus      = 'locked';          // Laba penuh terkunci
                    $profitNote        = null;
                } else {
                    // SKENARIO DP VIP / NEGOSIASI: DP lebih kecil dari target laba
                    // → Seluruh DP menjadi cicilan laba. Operasional TUNGGU PELUNASAN.
                    $fixedProfit       = $dpMasuk;          // 100% DP dikunci sebagai laba
                    $operationalBudget = 0;                 // Operasional = nol (belum ada dana)
                    $profitStatus      = 'partial_lock';    // Status khusus: laba baru sebagian
                    $profitNote        = "DP ({$dpMasuk}) lebih kecil dari target laba ({$targetProfit}). "
                                       . "Seluruh DP dikunci sebagai cicilan. Dana operasional menunggu pelunasan.";
                }

                // Safety Buffer HANYA dihitung jika ada operasional budget
                $safetyBufferAmt = $operationalBudget > 0 ? $operationalBudget * 0.10 : 0;
                $usableBudget    = $operationalBudget - $safetyBufferAmt;

                // Peringatan anggaran mepet
                $budgetWarning  = $operationalBudget > 0 && $usableBudget < 2000000;
                $warningMessage = $profitNote;
                if (!$warningMessage && $budgetWarning) {
                    $warningMessage = "Warning: Dana operasional bersih hanya Rp "
                                    . number_format($usableBudget, 0, ',', '.')
                                    . ". Sangat mepet untuk biaya lapangan!";
                }

                FinancialRecord::create([
                    'event_id'             => $event->id,
                    'total_revenue'        => $booking->total_price,
                    'fixed_profit_pct'     => $profitPct,
                    'is_profit_overridden' => $isOverridden,
                    'fixed_profit'         => $fixedProfit,
                    'dp_received'          => $dpMasuk,
                    'operational_budget'   => $operationalBudget,
                    'safety_buffer_pct'    => 10.00,
                    'safety_buffer_amt'    => $safetyBufferAmt,
                    'budget_warning'       => $budgetWarning || ($profitStatus === 'partial_lock'),
                    'warning_message'      => $warningMessage,
                    'profit_locked'        => true,
                    'status'               => $profitStatus === 'partial_lock' ? 'draft' : 'locked',
                ]);

                // Catatan: Function 'fn_estimate_total_honor' akan ditarik terpisah 
                // saat proses plotting personel di EventController dilakukan.
            });
            // ═══ COMMIT TRANSACTION ═══

            // Pesan disesuaikan dengan kondisi profit lock
            $successMsg = $profitStatus === 'partial_lock'
                ? '⚠️ DP Dikonfirmasi (Mode Cicil Laba). DP lebih kecil dari target laba — seluruh DP dikunci. Dana operasional menunggu pelunasan!'
                : '✅ DP Berhasil Dikonfirmasi. Laba Pimpinan '  . number_format($targetProfit, 0, ',', '.') . ' Telah Dikunci & Event Telah Dibuat!';

            return redirect()->back()->with('success', $successMsg);

        } catch (\Exception $e) {
            // ═══ ROLLBACK TRANSACTION otomatis terjadi di dalam DB::transaction ═══
            return redirect()->back()->with('error', 'Gagal mengonfirmasi transaksi: ' . $e->getMessage());
        }
    }

    /**
     * KONFIRMASI PEMBAYARAN DP SECARA TUNAI / OFFLINE
     * Untuk klien yang membayar langsung ke sanggar (tanpa upload bukti transfer).
     */
    public function confirmCashPayment(Request $request, Booking $booking)
    {
        if ($booking->status === 'dp_paid' || $booking->status === 'confirmed') {
            return redirect()->back()->with('error', 'Booking ini sudah dikonfirmasi sebelumnya.');
        }

        $request->validate([
            'fixed_profit_nominal' => 'required|numeric|min:0',
            'cash_note'            => 'nullable|string|max:255',
        ], [
            'fixed_profit_nominal.required' => 'Nominal Fixed Profit wajib diisi.',
        ]);

        try {
            $profitStatus = 'locked';
            $targetProfit = (float) $request->input('fixed_profit_nominal');

            DB::transaction(function () use ($booking, $request, &$targetProfit) {

                // Tandai booking sebagai DP PAID dengan catatan cash
                $booking->update([
                    'status'           => 'dp_paid',
                    'payment_proof'    => null,      // Tidak ada gambar struk
                    'payment_receipt'  => 'CASH_OFFLINE: ' . ($request->cash_note ?? 'Dibayar tunai di sanggar'),
                    'dp_paid_at'       => now(),
                ]);

                // Buat Event
                $eventCode = 'EVT-' . date('Y') . '-' . str_pad($booking->id, 3, '0', STR_PAD_LEFT);
                $event = Event::firstOrCreate(
                    ['booking_id' => $booking->id],
                    [
                        'event_code'      => $eventCode,
                        'status'          => 'planning',
                        'event_date'      => $booking->event_date,
                        'event_start'     => $booking->event_start,
                        'event_end'       => $booking->event_end,
                        'venue'           => $booking->venue,
                        'personnel_count' => 12,
                    ]
                );

                // Kunci laba dengan nominal manual
                $profitPct        = $booking->total_price > 0 ? ($request->fixed_profit_nominal / $booking->total_price) * 100 : 0;
                $dpMasuk          = $booking->dp_amount;
                $operationalBudget = max(0, $dpMasuk - $request->fixed_profit_nominal);
                $safetyBufferAmt  = $operationalBudget * 0.10;

                FinancialRecord::firstOrCreate(
                    ['event_id' => $event->id],
                    [
                        'total_revenue'        => $booking->total_price,
                        'fixed_profit_pct'     => round($profitPct, 2),
                        'is_profit_overridden' => true,
                        'fixed_profit'         => $request->fixed_profit_nominal,
                        'dp_received'          => $dpMasuk,
                        'operational_budget'   => $operationalBudget,
                        'safety_buffer_pct'    => 10.00,
                        'safety_buffer_amt'    => $safetyBufferAmt,
                        'budget_warning'       => $operationalBudget < 2000000,
                        'warning_message'      => 'Pembayaran DP via Tunai/Offline.',
                        'profit_locked'        => true,
                        'status'               => 'locked',
                    ]
                );
            });

            return redirect()->back()->with('success',
                '✅ [TUNAI] DP Dikonfirmasi! Laba Rp ' . number_format($targetProfit, 0, ',', '.') . ' telah dikunci. Event berhasil dibuat.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal konfirmasi cash: ' . $e->getMessage());
        }
    }
    
    /**
     * Form Booking Entry Manual
     */
    public function create()
    {
        return view('admin.bookings.create');
    }

    /**
     * ADMIN QUICK ENTRY: Simpan booking manual (Klien tanpa akun)
     */
    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_phone' => 'required|string|max:20',
            'event_type' => 'required|string',
            'event_date' => 'required|date',
            'event_start' => 'required',
            'event_end' => 'required',
            'venue' => 'required|string',
            'total_price' => 'required|numeric',
            'dp_amount' => 'required|numeric',
        ]);

        $validated['booking_source'] = 'admin_manual';
        $validated['status'] = 'pending';

        Booking::create($validated);

        return redirect()->route('admin.bookings.index')->with('success', 'Booking manual berhasil ditambahkan.');
    }
}
