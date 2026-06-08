<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Event;
use App\Models\FinancialRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Notifications\BookingStatusChanged;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Menampilkan daftar semua booking
     */
    public function index()
    {
        // Otomatis tandai event yang sudah lewat tanggalnya sebagai Selesai
        try {
            \Illuminate\Support\Facades\Artisan::call('events:auto-complete');
        } catch (\Exception $e) {
            // Abaikan jika gagal
        }

        $bookings = Booking::with('client')
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderByRaw("CASE WHEN status = 'pending' THEN created_at END ASC")
            ->orderByRaw("CASE WHEN status != 'pending' THEN created_at END DESC")
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
            ->orderBy('created_at', 'asc')
            ->get();

        // Booking pending yang belum ada bukti bayar (menunggu Klien)
        $pendingNoProof = Booking::with('client')
            ->where('status', 'pending')
            ->whereNull('payment_proof')
            ->orderBy('created_at', 'asc')
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

        $user = \App\Models\User::find($booking->client_id);
        if ($user) {
            $user->notify(new BookingStatusChanged($booking, 'Bukti pembayaran ditolak. Silakan upload ulang bukti yang valid.'));
        }

        return redirect()->back()->with('warning',
            '⚠️ Bukti Transfer dari ' . $booking->client_name . ' telah DITOLAK dan dihapus. Klien dapat melakukan upload ulang.');
    }

    /**
     * Konfirmasi Pelunasan Penuh (100%)
     */
    public function confirmFullPayment(Request $request, Booking $booking)
    {
        if (!in_array($booking->status, ['dp_paid', 'confirmed', 'completed'])) {
            return redirect()->back()->with('error', 'Status booking tidak valid untuk pelunasan.');
        }

        $booking->update([
            'status'       => 'paid_full',
            'full_paid_at' => now(),
        ]);

        // Jika sebelumnya partial_lock, maka ubah ke locked
        if ($booking->event && $booking->event->financialRecord) {
            $record = $booking->event->financialRecord;
            if ($record->status === 'draft') {
                $record->update(['status' => 'locked', 'warning_message' => null, 'budget_warning' => false]);
            }
        }

        // Update status event terkait jika ada
        if ($booking->event) {
            $booking->event->update(['status' => 'ready']);
        }

        $user = \App\Models\User::find($booking->client_id);
        if ($user) {
            $user->notify(new BookingStatusChanged($booking, 'telah LUNAS 100%. Terima kasih!'));
        }

        return redirect()->back()->with('success', 'Pelunasan 100% berhasil dikonfirmasi! Status booking: PAID (Lunas).');
    }

    /**
     * Tolak Bukti Pelunasan
     */
    public function rejectFullProof(Request $request, Booking $booking)
    {
        if (!in_array($booking->status, ['dp_paid', 'confirmed', 'completed'])) {
            return redirect()->back()->with('error', 'Status booking tidak valid untuk menolak pelunasan.');
        }

        if ($booking->full_payment_proof && Storage::disk('public')->exists($booking->full_payment_proof)) {
            Storage::disk('public')->delete($booking->full_payment_proof);
        }

        $booking->update([
            'full_payment_proof' => null,
        ]);

        $user = \App\Models\User::find($booking->client_id);
        if ($user) {
            $user->notify(new BookingStatusChanged($booking, 'Bukti pelunasan ditolak. Silakan upload ulang bukti yang valid.'));
        }

        return redirect()->back()->with('warning', '⚠️ Bukti Pelunasan dari ' . $booking->client_name . ' telah DITOLAK dan dihapus.');
    }

    /**
     * Pelunasan Tunai (CASH)
     */
    public function confirmFullCashPayment(Request $request, Booking $booking)
    {
        if (!in_array($booking->status, ['dp_paid', 'confirmed', 'completed'])) {
            return redirect()->back()->with('error', 'Status booking tidak valid untuk pelunasan.');
        }

        $request->validate([
            'cash_note' => 'nullable|string|max:255',
        ]);

        $booking->update([
            'status'             => 'paid_full',
            'full_paid_at'       => now(),
            'full_payment_proof' => 'CASH_OFFLINE: ' . ($request->cash_note ?? 'Dibayar tunai pelunasan di sanggar'),
        ]);

        if ($booking->event) {
            $booking->event->update(['status' => 'ready']);

            if ($booking->event->financialRecord) {
                $record = $booking->event->financialRecord;
                if ($record->status === 'draft') {
                    $record->update(['status' => 'locked', 'warning_message' => null, 'budget_warning' => false]);
                }
            }
        }

        $user = \App\Models\User::find($booking->client_id);
        if ($user) {
            $user->notify(new BookingStatusChanged($booking, 'telah LUNAS 100% secara TUNAI (Cash). Terima kasih!'));
        }

        return redirect()->back()->with('success', '✅ Pelunasan Tunai (CASH) berhasil dikonfirmasi! Status booking: PAID (Lunas).');
    }

    /**
     * Update harga total saat negosiasi
     */
    public function updatePrice(Request $request, Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Celah keamanan: Harga tidak dapat dimodifikasi karena laba sudah dikunci (Status: ' . strtoupper($booking->status) . ').');
        }
        $request->validate([
            'total_price' => 'required|numeric|min:0',
        ]);

        $booking->update([
            'total_price' => $request->total_price,
            // Re-adjust DP minimum based on new total
            'dp_amount' => $request->total_price * 0.50,
        ]);

        $user = \App\Models\User::find($booking->client_id);
        if ($user) {
            $user->notify(new BookingStatusChanged($booking, 'harganya telah diperbarui menjadi Rp ' . number_format($request->total_price, 0, ',', '.')));
        }

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
                // FIX A-01: Kunci data booking secara eksklusif (Pessimistic Locking)
                $lockedBooking = Booking::where('id', $booking->id)->lockForUpdate()->firstOrFail();

                if (in_array($lockedBooking->status, ['dp_paid', 'confirmed', 'paid_full', 'completed'])) {
                    throw new \Exception('Booking ini sudah dikonfirmasi sebelumnya oleh admin lain.');
                }

                // 1. UPDATE STATUS BOOKING & BUKTI TRANSFER
                $receiptPath = $request->input('receipt_path', $lockedBooking->payment_receipt);

                $lockedBooking->update([
                    'status' => 'dp_paid',
                    'payment_receipt' => $receiptPath,
                    'dp_paid_at' => now(),
                ]);

                // 2. BUAT ENTRI EVENT OTOMATIS
                $baseCode = 'EVT-' . date('Y') . '-' . str_pad($lockedBooking->id, 3, '0', STR_PAD_LEFT);
                $eventCode = $baseCode;
                $counter = 1;
                while (Event::where('event_code', $eventCode)->exists()) {
                    $eventCode = $baseCode . '-' . $counter;
                    $counter++;
                }

                $event = Event::create([
                    'booking_id'      => $lockedBooking->id,
                    'event_code'      => $eventCode,
                    'status'          => 'planning',
                    'event_date'      => $lockedBooking->event_date,
                    'event_start'     => $lockedBooking->event_start,
                    'event_end'       => $lockedBooking->event_end,
                    'venue'           => $lockedBooking->venue,
                    'latitude'        => $lockedBooking->latitude,
                    'longitude'       => $lockedBooking->longitude,
                    'personnel_count' => ($lockedBooking->serviceCatalog?->max_personnel > 0) ? $lockedBooking->serviceCatalog->max_personnel : 12,
                ]);

                // 3. KUNCI FIXED PROFIT — INPUT MANUAL DARI ADMIN (bukan otomatis 30%)
                // ══════════════════════════════════════════════════════════════
                // Pimpinan Sanggar menentukan nominal keuntungan langsung (misal: Rp 2.500.000)
                // bukan persentase baku, sesuai hasil wawancara.
                // ══════════════════════════════════════════════════════════════
                $targetProfit = (float) $request->input('fixed_profit_nominal');
                // Hitung persen informasional (untuk dicatat di DB, bukan untuk kalkulasi)
                $profitPct    = $lockedBooking->total_price > 0 ? ($targetProfit / $lockedBooking->total_price) * 100 : 0;
                $isOverridden = true; // Selalu true karena manual
                $dpMasuk      = $lockedBooking->dp_amount;                         // e.g. Rp 1.5 Jt (DP VIP kecil)

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
                    'total_revenue'        => $lockedBooking->total_price,
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
            $user = \App\Models\User::find($booking->client_id);
            if ($user) {
                $user->notify(new BookingStatusChanged($booking, 'telah dikonfirmasi (DP Masuk).'));
            }

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
                // FIX A-01: Lock data booking
                $lockedBooking = Booking::where('id', $booking->id)->lockForUpdate()->firstOrFail();

                if (in_array($lockedBooking->status, ['dp_paid', 'confirmed', 'paid_full', 'completed'])) {
                    throw new \Exception('Booking ini sudah dikonfirmasi sebelumnya oleh admin lain.');
                }

                // Tandai booking sebagai DP PAID dengan catatan cash
                $lockedBooking->update([
                    'status'           => 'dp_paid',
                    'payment_proof'    => null,      // Tidak ada gambar struk
                    'payment_receipt'  => 'CASH_OFFLINE: ' . ($request->cash_note ?? 'Dibayar tunai di sanggar'),
                    'dp_paid_at'       => now(),
                ]);

                // Buat Event
                $baseCode = 'EVT-' . date('Y') . '-' . str_pad($lockedBooking->id, 3, '0', STR_PAD_LEFT);
                $eventCode = $baseCode;
                $counter = 1;
                while (Event::where('event_code', $eventCode)->where('booking_id', '!=', $lockedBooking->id)->exists()) {
                    $eventCode = $baseCode . '-' . $counter;
                    $counter++;
                }
                $event = Event::firstOrCreate(
                    ['booking_id' => $lockedBooking->id],
                    [
                        'event_code'      => $eventCode,
                        'status'          => 'planning',
                        'event_date'      => $lockedBooking->event_date,
                        'event_start'     => $lockedBooking->event_start,
                        'event_end'       => $lockedBooking->event_end,
                        'venue'           => $lockedBooking->venue,
                        'personnel_count' => ($lockedBooking->serviceCatalog?->max_personnel > 0) ? $lockedBooking->serviceCatalog->max_personnel : 12,
                    ]
                );

                // Kunci laba dengan nominal manual
                // FIX A-05: Menggunakan variabel terikat $targetProfit
                $profitPct        = $lockedBooking->total_price > 0 ? ($targetProfit / $lockedBooking->total_price) * 100 : 0;
                $dpMasuk          = $lockedBooking->dp_amount;
                $operationalBudget = max(0, $dpMasuk - $targetProfit);
                $safetyBufferAmt  = $operationalBudget * 0.10;

                FinancialRecord::firstOrCreate(
                    ['event_id' => $event->id],
                    [
                        'total_revenue'        => $lockedBooking->total_price,
                        'fixed_profit_pct'     => round($profitPct, 2),
                        'is_profit_overridden' => true,
                        'fixed_profit'         => $targetProfit,
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

            $user = \App\Models\User::find($booking->client_id);
            if ($user) {
                $user->notify(new BookingStatusChanged($booking, 'pembayaran tunai (Cash) telah dikonfirmasi (DP Masuk).'));
            }

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
            'event_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $exists = Booking::where('event_date', $value)
                        ->whereIn('status', ['dp_paid', 'confirmed', 'paid_full', 'completed'])
                        ->exists();
                    if ($exists) {
                        $fail('Tanggal ' . Carbon::parse($value)->format('d M Y') . ' sudah penuh/di-booking dan dikunci oleh klien lain.');
                    }
                },
            ],
            'event_start' => 'required',
            'event_end' => 'required',
            'venue' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'total_price' => 'required|numeric',
            'dp_amount' => 'required|numeric',
        ]);

        $validated['booking_source'] = 'admin_manual';
        $validated['status'] = 'pending';

        $booking = Booking::create($validated);

        // Also create/update event with coordinates if provided
        if (!empty($validated['latitude']) && !empty($validated['longitude'])) {
            Event::create([
                'booking_id' => $booking->id,
                'event_type' => $validated['event_type'],
                'event_date' => $validated['event_date'],
                'event_start' => $validated['event_start'],
                'event_end' => $validated['event_end'],
                'venue' => $validated['venue'],
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
                'status' => 'planning'
            ]);
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Booking manual berhasil ditambahkan.');
    }
}
