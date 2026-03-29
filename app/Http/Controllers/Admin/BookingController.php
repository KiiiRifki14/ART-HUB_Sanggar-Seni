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
        $bookings = Booking::with('client')->latest()->get();
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
            // ═══ MULAI SQL TRANSACTION ═══
            // Semua query di dalam closure ini dibungkus START TRANSACTION dan COMMIT otomatis.
            // Jika ada Exception, otomatis di-ROLLBACK.
            DB::transaction(function () use ($booking, $request) {
                
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

                // 3. HITUNG DAN KUNCI FIXED PROFIT (Laba Pak Yat)
                // Cek apakah ada override % profit dari form, jika tidak pakai default 30%
                $profitPct = $request->input('override_profit_pct', 30.00);
                $isOverridden = $request->has('override_profit_pct');
                
                $fixedProfit = $booking->total_price * ($profitPct / 100);
                $operationalBudget = $booking->dp_amount - $fixedProfit;
                
                // Kalkulasi Safety Buffer (10% dari operasional)
                $safetyBufferAmt = $operationalBudget * 0.10;
                $usableBudget = $operationalBudget - $safetyBufferAmt;

                // Logika Peringatan Dana Darurat (Misal: operasional murni < Rp 2 Juta → beri warning)
                $budgetWarning = $usableBudget < 2000000;
                $warningMessage = $budgetWarning 
                    ? "Warning: Dana operasional bersih (setelah profit & buffer) hanya Rp " . number_format($usableBudget, 0, ',', '.') . ". Sangat mepet untuk biaya lapangan!" 
                    : null;

                // Insert into financial_records
                FinancialRecord::create([
                    'event_id'             => $event->id,
                    'total_revenue'        => $booking->total_price,
                    'fixed_profit_pct'     => $profitPct,
                    'is_profit_overridden' => $isOverridden,
                    'fixed_profit'         => $fixedProfit,
                    'dp_received'          => $booking->dp_amount,
                    'operational_budget'   => $operationalBudget,
                    'safety_buffer_pct'    => 10.00,
                    'safety_buffer_amt'    => $safetyBufferAmt,
                    'budget_warning'       => $budgetWarning,
                    'warning_message'      => $warningMessage,
                    'profit_locked'        => true, // MENGUNCI LABA
                    'status'               => 'locked',
                ]);

                // Catatan: Function 'fn_estimate_total_honor' akan ditarik terpisah 
                // saat proses plotting personel di EventController dilakukan.
            });
            // ═══ COMMIT TRANSACTION ═══

            return redirect()->back()->with('success', 'DP Berhasil Dikonfirmasi. Laba Pimpinan Telah Dikunci & Event Telah Dibuat!');

        } catch (\Exception $e) {
            // ═══ ROLLBACK TRANSACTION otomatis terjadi di dalam DB::transaction ═══
            return redirect()->back()->with('error', 'Gagal mengonfirmasi transaksi: ' . $e->getMessage());
        }
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
