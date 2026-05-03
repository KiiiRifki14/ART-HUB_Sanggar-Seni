<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\RehearsalController;
use App\Http\Controllers\Admin\CancellationController;
use App\Http\Controllers\Admin\CostumeController;
use App\Http\Controllers\Admin\FinancialController;
use App\Http\Controllers\Admin\PersonnelController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Personnel\AttendanceController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

// 👇 PENGATUR LALU LINTAS ROLE
Route::get('/dashboard', function () {
    $role = Auth::user()->role;

    if ($role === 'admin') return redirect()->route('admin.dashboard');
    if ($role === 'personel') return redirect()->route('personnel.dashboard');
    return redirect()->route('klien.dashboard');
})->middleware(['auth'])->name('dashboard');

// Profil dari Breeze
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destro');
});

// ══════════════════════════════════════════════════════════════════════════
// 👑 1. ADMIN ROUTES (Gudang Logika Pak Yat)
// ══════════════════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        // ── STAT CARDS (data nyata)
        $lockedProfit  = \App\Models\FinancialRecord::where('profit_locked', true)->sum('fixed_profit');
        $safetyBuffer  = \App\Models\FinancialRecord::sum('safety_buffer_amt');
        $totalPenalty  = \Illuminate\Support\Facades\DB::table('event_personnel')
                            ->where('attendance_status', 'late')
                            ->where('late_minutes', '>', 0)
                            ->selectRaw('SUM(late_minutes / 10 * 15000) as total')
                            ->value('total') ?? 0;
        $lateCount     = \Illuminate\Support\Facades\DB::table('event_personnel')
                            ->where('attendance_status', 'late')->count();
        $eventCount    = \App\Models\Event::whereMonth('event_date', now()->month)->count();
        $needPlotting  = \App\Models\Event::where('status', 'planning')->count();

        // ── UPCOMING EVENTS (max 3)
        $upcomingEvents = \App\Models\Event::with('booking')
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date')
            ->limit(3)
            ->get();

        // ── CHART 1: Revenue per bulan (Dari 2 bulan lalu hingga 3 bulan ke depan, agar testing masuk)
        $revenueChart = collect(range(2, -3))->map(function ($monthsAgo) {
            $month = now()->subMonths($monthsAgo);
            $revenue = \App\Models\FinancialRecord::whereHas('event', function ($q) use ($month) {
                $q->whereMonth('event_date', $month->month)
                  ->whereYear('event_date', $month->year);
            })->sum('total_revenue');
            $profit = \App\Models\FinancialRecord::whereHas('event', function ($q) use ($month) {
                $q->whereMonth('event_date', $month->month)
                  ->whereYear('event_date', $month->year);
            })->sum('fixed_profit');
            return [
                'label'   => $month->translatedFormat('M Y'),
                'revenue' => (int) $revenue,
                'profit'  => (int) $profit,
            ];
        });

        // ── CHART 2: Distribusi status booking
        $statusChart = \App\Models\Booking::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // ── FORMAT CHART JSON payload
        $statusInfo = [
            'pending'   => ['Negotiation', '#fbbf24'],
            'dp_paid'   => ['Locked',      '#f97316'],
            'confirmed' => ['DP 50%',      '#60a5fa'],
            'paid_full' => ['Lunas',       '#4ade80'],
            'completed' => ['Completed',   '#86efac'],
            'cancelled' => ['Cancelled',   '#555'],
        ];
        $stLabels = []; $stData = []; $stColors = [];
        foreach($statusChart as $st => $cnt) {
            $info = $statusInfo[$st] ?? [$st, '#888'];
            $stLabels[] = $info[0];
            $stData[]   = (int) $cnt;
            $stColors[] = $info[1];
        }
        $chartPayload = json_encode([
            'revenue' => $revenueChart->values()->all(),
            'stLabels' => $stLabels,
            'stData'   => $stData,
            'stColors' => $stColors,
        ]);

        return view('admin.dashboard', compact(
            'lockedProfit', 'safetyBuffer', 'totalPenalty', 'lateCount',
            'eventCount', 'needPlotting', 'upcomingEvents',
            'revenueChart', 'statusChart', 'statusInfo', 'chartPayload'
        ));
    })->name('dashboard');

    // BOOKINGS
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/manual', [BookingController::class, 'storeManual'])->name('bookings.manual.store');
    Route::post('/bookings/{booking}/confirm', [BookingController::class, 'confirmPayment'])->name('bookings.confirm');
    Route::patch('/bookings/{booking}/price', [BookingController::class, 'updatePrice'])->name('bookings.update_price');

    // EVENTS
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/plotting', [EventController::class, 'plotting'])->name('events.plotting');
    Route::post('/events/{event}/plotting', [EventController::class, 'storePlotting'])->name('events.plotting.store');
    Route::patch('/events/{event}/coordinates', [EventController::class, 'updateCoordinates'])->name('events.update_coordinates');

    // PERSONNEL MANAGEMENT (CRUD lengkap)
    Route::get('/personnel', [PersonnelController::class, 'index'])->name('personnel.index');
    Route::get('/personnel/create', [PersonnelController::class, 'create'])->name('personnel.create');
    Route::post('/personnel', [PersonnelController::class, 'store'])->name('personnel.store');
    Route::get('/personnel/{personnel}/edit', [PersonnelController::class, 'edit'])->name('personnel.edit');
    Route::put('/personnel/{personnel}', [PersonnelController::class, 'update'])->name('personnel.update');
    Route::delete('/personnel/{personnel}', [PersonnelController::class, 'destroy'])->name('personnel.destroy');


    // PAYMENT TRACKING
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');

    // COSTUMES
    Route::get('/costumes', [CostumeController::class, 'index'])->name('costumes.index');
    Route::post('/costume-usages/{usage}/return', [CostumeController::class, 'returnSanggarCostume'])->name('costumes.usage.return');
    Route::post('/costume-rentals/{rental}/return', [CostumeController::class, 'returnVendorRental'])->name('costumes.rental.return');

    // FINANCIAL
    Route::get('/financials', [FinancialController::class, 'index'])->name('financials.index');
    Route::get('/financials/post-event/{event}', [FinancialController::class, 'postEvent'])->name('financials.post_event');
    Route::post('/financials/operational-costs/{cost}', [FinancialController::class, 'updateOperationalCost'])->name('financials.operational_costs.update');

    // CANCELLATION HANDLER
    Route::get('/cancellations', [CancellationController::class, 'index'])->name('cancellations.index');
    Route::post('/bookings/{booking}/cancel', [CancellationController::class, 'store'])->name('bookings.cancel');

    // REHEARSALS
    Route::get('/rehearsals', [RehearsalController::class, 'index'])->name('rehearsals.index');
    Route::post('/events/{event}/rehearsals', [RehearsalController::class, 'store'])->name('rehearsals.store');

    // EVENT MONITORING (halaman baru)
    Route::get('/monitoring', [EventController::class, 'monitoring'])->name('events.monitoring');
    Route::get('/monitoring/{event}', [EventController::class, 'monitoringDetail'])->name('events.monitoring.show');

    // DP VERIFICATION (halaman mandiri)
    Route::get('/dp-verification', [BookingController::class, 'dpVerification'])->name('bookings.dp_verification');
    Route::post('/bookings/{booking}/reject-proof', [BookingController::class, 'rejectProof'])->name('bookings.reject_proof');

    // UPDATE PRICE (NEGO VIA WA)
    Route::patch('/bookings/{booking}/update-price', [BookingController::class, 'updatePrice'])->name('bookings.update_price');

    // FULL PAYMENT CONFIRM
    Route::patch('/bookings/{booking}/full-payment', [BookingController::class, 'confirmFullPayment'])->name('bookings.full_payment');

    // KONFIRMASI DP TUNAI / CASH (Offline)
    Route::post('/bookings/{booking}/confirm-cash', [BookingController::class, 'confirmCashPayment'])->name('bookings.confirm_cash');

    // POST-EVENT LIST (menu mandiri)
    Route::get('/post-event', [FinancialController::class, 'postEventList'])->name('financials.post_event_list');

    // TAMBAH BIAYA OPERASIONAL BARU (Post-Event)
    Route::post('/financials/post-event/{event}/costs', [FinancialController::class, 'storeOperationalCost'])->name('financials.operational_costs.store');

    // TANDAI EVENT SELESAI (Fix Bug Status Gantung)
    Route::patch('/events/{event}/complete', [EventController::class, 'markCompleted'])->name('events.mark_completed');
});

// ══════════════════════════════════════════════════════════════════════════
// 🎭 2. PERSONNEL ROUTES (Kru & Penari)
// ══════════════════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:personel'])->prefix('personnel')->name('personnel.')->group(function () {
    
    // Rute Bebas Akses (Hanya Untuk Menunggu)
    Route::get('/pending', function () {
        return view('personnel.pending');
    })->name('pending');

    // Rute khusus Personel Aktif
    Route::middleware([\App\Http\Middleware\EnsurePersonnelIsActive::class])->group(function () {
        Route::get('/dashboard', function () {
            return view('personnel.dashboard');
        })->name('dashboard');

        Route::post('/events/{event}/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check_in');
    });
});

// ══════════════════════════════════════════════════════════════════════════
// 🤝 3. KLIEN ROUTES (Penyewa Event)
// ══════════════════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:klien'])->prefix('klien')->name('klien.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Klien\BookingController::class, 'index'])->name('dashboard');
    Route::get('/bookings/create', [\App\Http\Controllers\Klien\BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [\App\Http\Controllers\Klien\BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{id}', [\App\Http\Controllers\Klien\BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{id}/proof', [\App\Http\Controllers\Klien\BookingController::class, 'uploadProof'])->name('bookings.upload_proof');
});

// Memuat Rute Login/Register yang dibuat oleh Breeze
require __DIR__ . '/auth.php';
