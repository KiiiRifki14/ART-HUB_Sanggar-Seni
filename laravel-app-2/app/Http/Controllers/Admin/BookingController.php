<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Event;
use App\Models\FinancialRecord;
use Illuminate\Support\Facades\DB;
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
     * MENGONFIRMASI PEMBAYARAN DP DAN MENGUNCI LABA
     * Sesuai standar materi Basis Data 2 (SQL Transaction).
     */
    public function confirmPayment(Request $request, Booking $booking)
    {
        // Validasi state agar tidak double confirm
        if ($booking->status === 'dp_paid' || $booking->status === 'confirmed') {
            return redirect()->back()->with('error', 'Booking ini sudah dikonfirmasi sebelumnya.');
        }

        try {
            // Variabel yang perlu dibaca di luar closure transaction
            $profitStatus = 'locked';
            $targetProfit = 0;

            DB::transaction(function () use ($booking, $request, &$profitStatus, &$targetProfit) {
                
                // 1. UPDATE STATUS BOOKING & BUKTI TRANSFER
                // (Dalam skenario nyata, upload file diproses di sini dan path-nya disimpan)
                $receiptPath = $request->input('receipt_path', $booking->payment_receipt); 

                $booking->update([
                    'status' => 'dp_paid',
                    'payment_receipt' => $receiptPath,
                    'dp_paid_at' => now(),
                ]);

                // 2. BUAT ENTRI EVENT OTOMATIS
                // Menyalin data dari booking ke tabel events untuk persiapan operasional
                $eventCode = 'EVT-' . date('Y') . '-' . str_pad($booking->id, 3, '0', STR_PAD_LEFT);
                
                $event = Event::create([
                    'booking_id'      => $booking->id,
                    'event_code'      => $eventCode,
                    'status'          => 'planning',
                    'event_date'      => $booking->event_date,
                    'event_start'     => $booking->event_start,
                    'event_end'       => $booking->event_end,
                    'venue'           => $booking->venue,
                    'personnel_count' => 12, // Standar 11+1
                ]);

                // 3. HITUNG DAN KUNCI FIXED PROFIT (Laba Pak Yat) — CELAH #1 FIX
                // ══════════════════════════════════════════════════════════════
                // ATURAN "PROFIT FIRST — DP CICIL LABA":
                //   - Laba 30 % dihitung dari TOTAL, BUKAN dari DP.
                //   - Jika DP ≥ target laba  → potong laba di muka, sisa DP → modal operasional.
                //   - Jika DP < target laba  → SELURUH DP dikunci sebagai cicilan laba,
                //     modal operasional = 0, sistem mencatat ini sebagai "partial_lock".
                //     Operasional HANYA cair setelah pelunasan masuk.
                // ══════════════════════════════════════════════════════════════
                $profitPct   = $request->input('override_profit_pct', 30.00);
                $isOverridden = $request->has('override_profit_pct');

                $targetProfit = $booking->total_price * ($profitPct / 100); // Rp 3 Jt dari Rp 10 Jt
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
