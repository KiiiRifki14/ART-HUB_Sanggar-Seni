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
    public function index(Request $request)
    {
        // Otomatis tandai event yang sudah lewat tanggalnya sebagai Selesai
        try {
            \Illuminate\Support\Facades\Artisan::call('events:auto-complete');
        } catch (\Exception $e) {
            // Abaikan jika gagal
        }

        // Hitung statistik data booking secara penuh (tanpa limit paginasi/filter)
        $total    = Booking::count();
        $pending  = Booking::where('status', 'pending')->count();
        $dpPaid   = Booking::where('status', 'dp_paid')->count();
        $done     = Booking::whereIn('status', ['confirmed', 'completed'])->count();
        $canceled = Booking::where('status', 'cancelled')->count();

        $status = $request->input('status', 'all');
        $search = $request->input('search');

        $query = Booking::with('client');

        if ($status !== 'all') {
            if ($status === 'completed') {
                $query->whereIn('status', ['confirmed', 'completed']);
            } else {
                $query->where('status', $status);
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('client_name', 'like', "%{$search}%")
                  ->orWhere('client_phone', 'like', "%{$search}%")
                  ->orWhere('client_email', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%")
                  ->orWhere('venue_address', 'like', "%{$search}%")
                  ->orWhere('event_type', 'like', "%{$search}%")
                  ->orWhereHas('client', function ($subQuery) use ($search) {
                      $subQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $bookings = $query->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderByRaw("CASE WHEN status = 'pending' THEN created_at END ASC")
            ->orderByRaw("CASE WHEN status != 'pending' THEN created_at END DESC")
            ->paginate(10)
            ->withQueryString();

        // Inject Smart Warning into pending bookings
        foreach ($bookings as $b) {
            if ($b->status === 'pending' && !$b->is_admin_confirmed) {
                $b->smart_warning = $this->calculateSmartWarning($b);
            }
        }

        return view('admin.bookings.index', compact('bookings', 'total', 'pending', 'dpPaid', 'done', 'canceled'));
    }

    /**
     * Menampilkan detail satu booking
     */
    public function show(Booking $booking)
    {
        $booking->load(['client', 'event.financialRecord']);
        if ($booking->status === 'pending' && !$booking->is_admin_confirmed) {
            $booking->smart_warning = $this->calculateSmartWarning($booking);
        }
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * DP VERIFICATION: Inbox mandiri untuk validasi bukti DP
     * Versi baru dengan Summary Stats (Antrean, Total DP Masuk, Profit Terkunci)
     */
    public function dpVerification()
    {
        // ── SUMMARY CARD 1: Jumlah antrean menunggu verifikasi (dihitung dari database secara utuh)
        $antreanCount = Booking::where('status', 'pending')
            ->whereNotNull('payment_proof')
            ->count();

        // Booking pending dengan bukti bayar sudah di-upload (menunggu konfirmasi Admin)
        $pendingWithProof = Booking::with('client')
            ->where('status', 'pending')
            ->whereNotNull('payment_proof')
            ->orderBy('created_at', 'asc')
            ->paginate(10, ['*'], 'page_with_proof')
            ->withQueryString();

        // Booking pending yang belum ada bukti bayar (menunggu Klien)
        $pendingNoProof = Booking::with('client')
            ->where('status', 'pending')
            ->whereNull('payment_proof')
            ->orderBy('created_at', 'asc')
            ->paginate(10, ['*'], 'page_no_proof')
            ->withQueryString();

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
     * TERIMA BOOKING (Konfirmasi Awal)
     * Klien baru bisa bayar DP setelah ini.
     */
    public function acceptBooking(Booking $booking)
    {
        if ($booking->status !== 'pending' || $booking->is_admin_confirmed) {
            return redirect()->back()->with('error', 'Status booking tidak valid untuk konfirmasi awal.');
        }

        $booking->update([
            'is_admin_confirmed' => true,
        ]);

        $user = \App\Models\User::find($booking->client_id);
        if ($user) {
            $user->notify(new BookingStatusChanged($booking, 'telah DITERIMA oleh Admin. Silakan lanjutkan ke pembayaran DP.'));
        }

        return redirect()->back()->with('success', 'Booking berhasil dikonfirmasi. Klien sekarang dapat mengunggah bukti pembayaran DP.');
    }

    /**
     * TOLAK BOOKING
     * Membatalkan pesanan dari klien dengan alasan tertentu.
     */
    public function rejectBooking(Request $request, Booking $booking)
    {
        if ($booking->status !== 'pending' || $booking->is_admin_confirmed) {
            return redirect()->back()->with('error', 'Status booking tidak valid untuk penolakan.');
        }

        $request->validate([
            'admin_note' => 'required|string|max:500',
        ]);

        $booking->update([
            'status' => 'cancelled',
            'admin_note' => $request->admin_note,
        ]);

        // Tambahkan ke tabel cancellations agar tercatat di history pembatalan
        \App\Models\Cancellation::create([
            'booking_id'              => $booking->id,
            'cancellation_date'       => \Carbon\Carbon::now()->format('Y-m-d'),
            'days_before_event'       => max(0, (int) ceil(\Carbon\Carbon::now()->diffInHours(\Carbon\Carbon::parse($booking->event_date), false) / 24)),
            'penalty_percentage'      => 0,
            'penalty_amount'          => 0,
            'refund_amount'           => 0,
            'status'                  => 'processed',
            'reason'                  => 'Ditolak Admin: ' . $request->admin_note,
            'digital_acknowledgement' => true,
            'acknowledged_ip'         => $request->ip(),
            'acknowledged_at'         => now(),
            'acknowledged_ua'         => $request->userAgent(),
        ]);

        $user = \App\Models\User::find($booking->client_id);
        if ($user) {
            $user->notify(new \App\Notifications\BookingStatusChanged($booking, 'DITOLAK oleh Admin. Alasan: ' . $request->admin_note));
        }

        return redirect()->back()->with('warning', 'Booking berhasil ditolak dan tercatat di histori pembatalan.');
    }

    /**
     * UPDATE JADWAL BOOKING (Nego Jadwal)
     */
    public function updateSchedule(Request $request, Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Jadwal hanya bisa diubah saat status pemesanan masih Menunggu Konfirmasi (Pending).');
        }

        $request->validate([
            'event_date'  => 'required|date|after_or_equal:today',
            'event_start' => 'required',
            'event_end'   => 'required|after:event_start',
        ]);

        $booking->update([
            'event_date'  => $request->event_date,
            'event_start' => $request->event_start,
            'event_end'   => $request->event_end,
        ]);

        return redirect()->back()->with('success', 'Jadwal pementasan berhasil diperbarui. Silakan cek kembali ketersediaan personel pada tanggal baru.');
    }

    /**
     * Hitung Smart Warning untuk Ketersediaan Personel
     */
    public function calculateSmartWarning(Booking $booking)
    {
        $date = Carbon::parse($booking->event_date)->format('Y-m-d');
        $catalog = $booking->serviceCatalog;
        
        // 1. Total Personel Aktif
        $availableTotal = \App\Models\Personnel::where('is_active', true)->count();
        $availablePenari = \App\Models\Personnel::where('is_active', true)->whereIn('specialty', ['penari', 'multi_talent'])->count();
        $availablePemusik = \App\Models\Personnel::where('is_active', true)->whereIn('specialty', ['pemusik', 'multi_talent'])->count();
        
        // 2. Personel yang berhalangan
        $unavailableIds = \App\Models\PersonnelUnavailability::where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->pluck('personnel_id')
            ->toArray();
            
        if (!empty($unavailableIds)) {
            $availableTotal -= count($unavailableIds);
            $availablePenari -= \App\Models\Personnel::whereIn('id', $unavailableIds)->whereIn('specialty', ['penari', 'multi_talent'])->count();
            $availablePemusik -= \App\Models\Personnel::whereIn('id', $unavailableIds)->whereIn('specialty', ['pemusik', 'multi_talent'])->count();
        }
        
        // 3. Personel terpakai di booking lain (tanggal sama, sudah bayar DP/Lunas/Selesai)
        $otherBookings = Booking::with('serviceCatalog', 'event')
            ->where('event_date', $date)
            ->where('id', '!=', $booking->id)
            ->whereIn('status', ['dp_paid', 'paid_full', 'completed'])
            ->get();
            
        $usedTotal = 0;
        $usedPenari = 0;
        $usedPemusik = 0;
        
        foreach($otherBookings as $ob) {
            $req = $ob->event ? ($ob->event->personnel_count ?? 0) : ($ob->serviceCatalog?->max_personnel ?? 0);
            $usedTotal += $req;
            $spec = $ob->serviceCatalog?->specialty_type ?? 'gabungan';
            if ($spec === 'penari') $usedPenari += $req;
            elseif ($spec === 'pemusik') $usedPemusik += $req;
            else {
                $usedPenari += floor($req / 2);
                $usedPemusik += ceil($req / 2);
            }
        }
        
        $sisaTotal = max(0, $availableTotal - $usedTotal);
        $sisaPenari = max(0, $availablePenari - $usedPenari);
        $sisaPemusik = max(0, $availablePemusik - $usedPemusik);
        
        // Kebutuhan booking saat ini
        $reqTotal = $catalog?->max_personnel ?? 0;
        $reqType = $catalog?->specialty_type ?? 'gabungan';
        
        $statusClass = 'success';
        $message = '';

        if ($otherBookings->isEmpty()) {
            if ($sisaTotal >= $reqTotal) {
                $statusClass = 'success';
                $message = "Jadwal kosong. Seluruh personel aktif ({$sisaTotal} orang) siap bertugas. Sangat aman untuk dikonfirmasi.";
            } else {
                $statusClass = 'danger';
                $message = "Jadwal kosong, TAPI sisa personel aktif ({$sisaTotal} orang) kurang dari kebutuhan acara ({$reqTotal} orang).";
            }
        } else {
            if ($reqType === 'penari' && $sisaPenari < $reqTotal) {
                $statusClass = 'danger';
                $message = "Kapasitas spesifik tidak mencukupi! Pesanan ini membutuhkan {$reqTotal} Penari, namun saat ini hanya tersisa {$sisaPenari} Penari yang kosong.";
            } elseif ($reqType === 'pemusik' && $sisaPemusik < $reqTotal) {
                $statusClass = 'danger';
                $message = "Kapasitas spesifik tidak mencukupi! Pesanan ini membutuhkan {$reqTotal} Pemusik, namun saat ini hanya tersisa {$sisaPemusik} Pemusik yang kosong.";
            } elseif ($sisaTotal < $reqTotal) {
                $statusClass = 'danger';
                $message = "Kapasitas Tidak Mencukupi! Acara ini butuh {$reqTotal} orang, sedangkan sisa personel aktif hanya {$sisaTotal} orang. Disarankan untuk DITOLAK atau negosiasi jadwal.";
            } elseif ($sisaTotal == $reqTotal || ($sisaTotal - $reqTotal) <= 2) {
                $statusClass = 'warning';
                $message = "Peringatan Kritis: Terdapat {$otherBookings->count()} acara lain. Jika pesanan ini diterima, nyaris seluruh personel akan bertugas (sisa cadangan: " . ($sisaTotal - $reqTotal) . " orang). Harap pastikan tidak ada personel yang tiba-tiba berhalangan.";
            } else {
                // Cek kemungkinan Double Job jika waktu tidak bentrok
                $bentrokWaktu = false;
                $startA = Carbon::parse($booking->event_start)->format('H:i:s');
                $endA = Carbon::parse($booking->event_end)->format('H:i:s');
                
                foreach($otherBookings as $ob) {
                    $startB = Carbon::parse($ob->event_start)->format('H:i:s');
                    $endB = Carbon::parse($ob->event_end)->format('H:i:s');
                    if ($startA <= $endB && $endA >= $startB) {
                        $bentrokWaktu = true;
                        break;
                    }
                }
                
                if (!$bentrokWaktu) {
                    $statusClass = 'success';
                    $message = "Terdapat {$otherBookings->count()} acara lain di tanggal ini. Namun jam tayang berbeda, personel kemungkinan bisa melakukan 'Double Job'. Sisa personel murni: {$sisaTotal} orang (butuh {$reqTotal}).";
                } else {
                    $statusClass = 'success';
                    $message = "Terdapat {$otherBookings->count()} acara lain di tanggal ini. Namun sisa personel ({$sisaTotal} orang) masih mencukupi kebutuhan pesanan ini (butuh {$reqTotal} orang).";
                }
            }
        }
        
        return (object)[
            'class' => $statusClass,
            'message' => $message
        ];
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
