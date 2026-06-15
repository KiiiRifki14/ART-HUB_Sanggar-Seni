<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\RehearsalController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\CancellationController;
use App\Http\Controllers\Admin\ServiceCatalogController;
use App\Http\Controllers\Admin\CostumeController;
use App\Http\Controllers\Admin\FinancialController;
use App\Http\Controllers\Admin\PersonnelController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Personnel\AttendanceController;
use App\Http\Controllers\Personnel\PersonnelProfileController;
use App\Http\Controllers\Personnel\PersonnelUnavailabilityController;
use App\Http\Controllers\Personnel\FinancialController as PersonnelFinancialController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    $contents = \App\Models\SiteContent::pluck('value', 'key')->toArray();
    $personnels = \App\Models\Personnel::with('user')->where('is_active', true)->get();
    $catalogs = \App\Models\ServiceCatalog::where('is_active', true)->orderBy('sort_order')->orderBy('id')->paginate(6);
    return view('welcome', compact('contents', 'personnels', 'catalogs'));
});

// 👇 PENGATUR LALU LINTAS ROLE
Route::get('/dashboard', function () {
    $role = Auth::user()->role;

    if ($role === 'admin') return redirect()->route('admin.dashboard');
    if ($role === 'personel') return redirect()->route('personnel.dashboard');
    return redirect()->route('klien.dashboard');
})->middleware(['auth'])->name('dashboard');

// NOTIFICATIONS GLOBAL
Route::middleware('auth')->group(function () {
    Route::get('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.read_all');
});

// ══════════════════════════════════════════════════════════════════════════
// 👑 1. ADMIN ROUTES (Gudang Logika Pak Yat)
// ══════════════════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/cms', [CmsController::class, 'index'])->name('cms.index');
    Route::post('/cms', [CmsController::class, 'update'])->name('cms.update');

    Route::get('/dashboard', function () {
        // ── STAT CARDS (data nyata)
        $lockedProfit  = \App\Models\FinancialRecord::where('profit_locked', true)->sum('fixed_profit');
        $safetyBuffer  = \App\Models\FinancialRecord::sum('safety_buffer_amt');
        $latePenaltyRate = \App\Models\FeeReference::where('role_name', 'Denda Keterlambatan')->value('base_fee') ?? 15000;
        $totalPenalty  = \Illuminate\Support\Facades\DB::table('event_personnel')
                            ->where('attendance_status', 'late')
                            ->where('late_minutes', '>', 0)
                            ->selectRaw('SUM(late_minutes / 10 * ?)', [$latePenaltyRate])
                            ->value('total') ?? 0;
        $lateCount     = \Illuminate\Support\Facades\DB::table('event_personnel')
                            ->where('attendance_status', 'late')->count();
        $eventCount    = \App\Models\Event::whereMonth('event_date', now()->month)->count();
        $needPlotting  = \App\Models\Event::where('status', 'planning')->count();

        // ── UPCOMING EVENTS (max 3)
        $upcomingEvents = \App\Models\Event::with('booking')
            ->where('event_date', '>=', now()->toDateString())
            ->whereNotIn('status', ['completed', 'cancelled'])
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

        $pendingPersonnel = \App\Models\Personnel::where('status', 'pending_verification')->count();

        return view('admin.dashboard', compact(
            'lockedProfit', 'safetyBuffer', 'totalPenalty', 'lateCount',
            'eventCount', 'needPlotting', 'upcomingEvents',
            'revenueChart', 'statusChart', 'statusInfo', 'chartPayload',
            'pendingPersonnel'
        ));
    })->name('dashboard');

    // BOOKINGS
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/accept', [BookingController::class, 'acceptBooking'])->name('bookings.accept');
    Route::post('/bookings/{booking}/reject', [BookingController::class, 'rejectBooking'])->name('bookings.reject');
    Route::post('/bookings/manual', [BookingController::class, 'storeManual'])->name('bookings.manual.store');
    Route::post('/bookings/{booking}/confirm', [BookingController::class, 'confirmPayment'])->name('bookings.confirm');
    Route::patch('/bookings/{booking}/price', [BookingController::class, 'updatePrice'])->name('bookings.update_price');
    Route::patch('/bookings/{booking}/update-price', [BookingController::class, 'updatePrice']);
    Route::patch('/bookings/{booking}/schedule', [BookingController::class, 'updateSchedule'])->name('bookings.update_schedule');

    // EVENTS
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/plotting', [EventController::class, 'plotting'])->name('events.plotting');
    Route::post('/events/{event}/plotting', [EventController::class, 'storePlotting'])->name('events.plotting.store');
    Route::patch('/events/{event}/coordinates', [EventController::class, 'updateCoordinates'])->name('events.update_coordinates');
    Route::patch('/events/{event}/mark-completed', [EventController::class, 'markCompleted'])->name('events.mark_completed');

    // PERSONNEL MANAGEMENT (CRUD lengkap)
    Route::get('/personnel', [PersonnelController::class, 'index'])->name('personnel.index');
    Route::get('/personnel/create', [PersonnelController::class, 'create'])->name('personnel.create');
    Route::post('/personnel', [PersonnelController::class, 'store'])->name('personnel.store');
    Route::get('/personnel/{personnel}/edit', [PersonnelController::class, 'edit'])->name('personnel.edit');
    Route::put('/personnel/{personnel}', [PersonnelController::class, 'update'])->name('personnel.update');
    Route::delete('/personnel/{personnel}', [PersonnelController::class, 'destroy'])->name('personnel.destroy');
    Route::post('/personnel/{personnel}/approve', [PersonnelController::class, 'approve'])->name('personnel.approve');
    Route::delete('/personnel/{personnel}/reject', [PersonnelController::class, 'reject'])->name('personnel.reject');
    Route::patch('/personnel/{personnel}/toggle-status', [PersonnelController::class, 'toggleStatus'])->name('personnel.toggle_status');
    Route::patch('/events/{event}/personnel/{personnel}/status', [PersonnelController::class, 'updateEventStatus'])->name('personnel.update_event_status');


    // PAYMENT TRACKING
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');

    // COSTUMES
    Route::get('/costumes', [CostumeController::class, 'index'])->name('costumes.index');
    Route::post('/costume-usages/{usage}/return', [CostumeController::class, 'returnSanggarCostume'])->name('costumes.usage.return');
    Route::post('/costume-rentals/{rental}/return', [CostumeController::class, 'returnVendorRental'])->name('costumes.rental.return');
    Route::get('/costumes/create-asset', [CostumeController::class, 'createAsset'])->name('costumes.create-asset');
    Route::post('/costumes/store-asset', [CostumeController::class, 'storeAsset'])->name('costumes.store-asset');
    Route::get('/costumes/{costume}/edit-asset', [CostumeController::class, 'editAsset'])->name('costumes.edit-asset');
    Route::put('/costumes/{costume}/update-asset', [CostumeController::class, 'updateAsset'])->name('costumes.update-asset');
    Route::delete('/costumes/{costume}/destroy-asset', [CostumeController::class, 'destroyAsset'])->name('costumes.destroy-asset');
    // Rute API untuk Tambah Vendor (AJAX)
    Route::post('/costumes/vendor/api', [CostumeController::class, 'storeVendorApi'])->name('costumes.store-vendor-api');

    Route::get('/costumes/create-rental', [CostumeController::class, 'createRental'])->name('costumes.create-rental');
    Route::post('/costumes/store-rental', [CostumeController::class, 'storeRental'])->name('costumes.store-rental');
    Route::get('/costumes/{rental}/edit-rental', [CostumeController::class, 'editRental'])->name('costumes.edit-rental');
    Route::put('/costumes/{rental}/update-rental', [CostumeController::class, 'updateRental'])->name('costumes.update-rental');

    // FINANCIAL
    Route::get('/financials', [FinancialController::class, 'index'])->name('financials.index');
    Route::get('/financials/export-pdf', [FinancialController::class, 'exportPdf'])->name('financials.export_pdf');
    Route::get('/financials/post-event/{event}', [FinancialController::class, 'postEvent'])->name('financials.post_event');
    Route::post('/financials/operational-costs/{cost}', [FinancialController::class, 'updateOperationalCost'])->name('financials.operational_costs.update');

    // CANCELLATION HANDLER
    Route::get('/cancellations', [CancellationController::class, 'index'])->name('cancellations.index');
    Route::post('/bookings/{booking}/cancel', [CancellationController::class, 'store'])->name('bookings.cancel');
    Route::post('/cancellations/penalty-settings', [CancellationController::class, 'updatePenaltySettings'])->name('cancellations.penalty_settings');
    Route::post('/cancellations/{cancellation}/approve', [CancellationController::class, 'approveCancellation'])->name('cancellations.approve');
    Route::post('/cancellations/{cancellation}/reject', [CancellationController::class, 'rejectCancellation'])->name('cancellations.reject');

    // REHEARSALS
    Route::get('/rehearsals', [RehearsalController::class, 'index'])->name('rehearsals.index');
    Route::get('/rehearsals/create', [RehearsalController::class, 'create'])->name('rehearsals.create');
    Route::post('/events/{event}/rehearsals', [RehearsalController::class, 'store'])->name('rehearsals.store');
    Route::get('/rehearsals/{rehearsal}/edit', [RehearsalController::class, 'edit'])->name('rehearsals.edit');
    Route::put('/rehearsals/{rehearsal}', [RehearsalController::class, 'update'])->name('rehearsals.update');

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

    Route::post('/bookings/{booking}/reject-full-proof', [BookingController::class, 'rejectFullProof'])->name('bookings.reject_full_proof');
    Route::post('/bookings/{booking}/full-cash-payment', [BookingController::class, 'confirmFullCashPayment'])->name('bookings.full_cash_payment');

    // POST-EVENT LIST (menu mandiri)
    Route::get('/post-event', [FinancialController::class, 'postEventList'])->name('financials.post_event_list');

    // TAMBAH BIAYA OPERASIONAL BARU (Post-Event)
    Route::post('/financials/post-event/{event}/costs', [FinancialController::class, 'storeOperationalCost'])->name('financials.operational_costs.store');

    // CMS LANDING PAGE
    Route::get('/cms', [CmsController::class, 'index'])->name('cms.index');
    Route::post('/cms', [CmsController::class, 'update'])->name('cms.update');

    // KATALOG JASA
    Route::resource('catalogs', ServiceCatalogController::class)->except(['show']);
    Route::patch('/catalogs/{catalog}/toggle', [ServiceCatalogController::class, 'toggleActive'])->name('catalogs.toggle');

    // PENGATURAN PROFIL ADMIN
    Route::get('/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\Admin\AdminProfileController::class, 'updatePassword'])->name('profile.password');

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

        // Dashboard – passing data
        Route::get('/dashboard', function () {
            $user      = Auth::user();
            $personnel = $user->personnelProfile;
            $now       = now();

            // All upcoming events for stats, compact widget, etc.
            $upcomingEvents = $personnel
                ? $personnel->events()
                    ->where('event_date', '>=', $now->toDateString())
                    ->orderBy('event_date', 'asc')
                    ->get()
                : collect();

            // Rehearsals for personnel based on plotted events & specialty
            $upcomingRehearsals = collect();
            if ($personnel) {
                $specMap = ['penari' => 'tari', 'pemusik' => 'musik', 'multi_talent' => 'gabungan'];
                $mappedType = $specMap[$personnel->specialty] ?? 'gabungan';

                 $upcomingRehearsals = \App\Models\Rehearsal::with(['event.booking', 'personnel' => function($q) use ($personnel) {
                        $q->where('personnel.id', $personnel->id);
                    }])
                    ->whereHas('event.personnel', function($q) use ($personnel) {
                        $q->where('personnel.id', $personnel->id);
                    })
                    ->where(function($q) use ($mappedType) {
                        $q->where('type', 'gabungan');
                        if ($mappedType !== 'gabungan') {
                            $q->orWhere('type', $mappedType);
                        }
                    })
                    ->where('rehearsal_date', '>=', $now->toDateString())
                    ->orderBy('rehearsal_date', 'asc')
                    ->get();
            }

            // Paginated detailed tasks list (10 events per page)
            $paginatedDetailEvents = $personnel
                ? $personnel->events()
                    ->where('event_date', '>=', $now->toDateString())
                    ->orderBy('event_date', 'asc')
                    ->paginate(10, ['*'], 'detail_page')
                : new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);

            // Kalender navigation logic
            $thisMonth = request()->query('month', $now->month);
            $thisYear  = request()->query('year', $now->year);

            if (!is_numeric($thisMonth) || $thisMonth < 1 || $thisMonth > 12) $thisMonth = $now->month;
            if (!is_numeric($thisYear) || $thisYear < 2000 || $thisYear > 2100) $thisYear = $now->year;

            $firstDay    = \Carbon\Carbon::create($thisYear, $thisMonth, 1);
            $daysInMonth = $firstDay->daysInMonth;
            $startDow    = $firstDay->dayOfWeek;

            // Fetch events in selected month range for calendar markings
            $calendarEvents = $personnel
                ? $personnel->events()
                    ->whereBetween('event_date', [
                        $firstDay->copy()->startOfMonth()->toDateString(),
                        $firstDay->copy()->endOfMonth()->toDateString()
                    ])->get()
                : collect();

            $eventDates  = $calendarEvents->pluck('event_date')
                ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))->toArray();
            $urgentDates = $calendarEvents->filter(function ($e) use ($now) {
                $d = \Carbon\Carbon::parse($e->event_date)->startOfDay()->diffInDays($now->startOfDay(), false);
                return $d >= -3 && $d <= 0;
            })->pluck('event_date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))->toArray();

            // Rehearsal Dates for calendar markings
            $rehearsalDates = [];
            if ($personnel) {
                $calendarRehearsals = \App\Models\Rehearsal::whereHas('event.personnel', function($q) use ($personnel) {
                        $q->where('personnel.id', $personnel->id);
                    })
                    ->where(function($q) use ($mappedType) {
                        $q->where('type', 'gabungan');
                        if ($mappedType !== 'gabungan') {
                            $q->orWhere('type', $mappedType);
                        }
                    })
                    ->whereBetween('rehearsal_date', [
                        $firstDay->copy()->startOfMonth()->toDateString(),
                        $firstDay->copy()->endOfMonth()->toDateString()
                    ])->get();
                $rehearsalDates = $calendarRehearsals->pluck('rehearsal_date')
                    ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))->toArray();
            }


            // Prev & Next Month variables
            $prevMonthObj = $firstDay->copy()->subMonth();
            $nextMonthObj = $firstDay->copy()->addMonth();
            $prevMonth = $prevMonthObj->month;
            $prevYear  = $prevMonthObj->year;
            $nextMonth = $nextMonthObj->month;
            $nextYear  = $nextMonthObj->year;

            // Unavailabilities within selected month
            $unavailabilityDates = [];
            if ($personnel) {
                $unavailabilities = $personnel->unavailabilities()
                    ->where(function($query) use ($firstDay) {
                        $query->whereBetween('start_date', [
                            $firstDay->copy()->startOfMonth()->toDateString(),
                            $firstDay->copy()->endOfMonth()->toDateString()
                        ])->orWhereBetween('end_date', [
                            $firstDay->copy()->startOfMonth()->toDateString(),
                            $firstDay->copy()->endOfMonth()->toDateString()
                        ]);
                    })->get();
                foreach($unavailabilities as $unavail) {
                    $start = \Carbon\Carbon::parse($unavail->start_date);
                    $end = \Carbon\Carbon::parse($unavail->end_date);
                    for($date = $start; $date->lte($end); $date->addDay()) {
                        $unavailabilityDates[] = $date->format('Y-m-d');
                    }
                }
            }

            return view('personnel.dashboard', compact(
                'personnel', 'upcomingEvents', 'upcomingRehearsals', 'paginatedDetailEvents',
                'firstDay', 'daysInMonth', 'startDow', 'thisMonth', 'thisYear',
                'prevMonth', 'prevYear', 'nextMonth', 'nextYear',
                'eventDates', 'urgentDates', 'rehearsalDates', 'now', 'unavailabilityDates'
            ));
        })->name('dashboard');

        // Check-in GPS
        Route::post('/events/{event}/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check_in');
        Route::post('/rehearsals/{rehearsal}/check-in', [\App\Http\Controllers\Personnel\RehearsalAttendanceController::class, 'checkIn'])->middleware('throttle:6,1')->name('rehearsals.check_in');

        // Profil Mandiri
        Route::get('/profile', [PersonnelProfileController::class, 'edit'])->name('profile.edit');
        Route::post('/profile', [PersonnelProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/password', [PersonnelProfileController::class, 'updatePassword'])->name('profile.password');

        // Keuangan
        Route::get('/keuangan', [PersonnelFinancialController::class, 'index'])->name('keuangan');

        // Berhalangan
        Route::post('/unavailability', [PersonnelUnavailabilityController::class, 'store'])->name('unavailability.store');
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
    Route::post('/bookings/{id}/full-proof', [\App\Http\Controllers\Klien\BookingController::class, 'uploadFullProof'])->name('bookings.upload_full_proof');
    Route::post('/bookings/{booking}/feedback', [\App\Http\Controllers\Klien\ClientFeedbackController::class, 'store'])->name('bookings.feedback');
    Route::post('/bookings/{id}/cancel', [\App\Http\Controllers\Klien\BookingController::class, 'cancel'])->name('bookings.cancel');

    // PENGATURAN PROFIL KLIEN
    Route::get('/profile', [\App\Http\Controllers\Klien\KlienProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\Klien\KlienProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\Klien\KlienProfileController::class, 'updatePassword'])->name('profile.password');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Memuat Rute Login/Register yang dibuat oleh Breeze
require __DIR__ . '/auth.php';
