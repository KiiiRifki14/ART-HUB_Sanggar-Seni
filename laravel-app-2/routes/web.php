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
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ══════════════════════════════════════════════════════════════════════════
// 👑 1. ADMIN ROUTES (Gudang Logika Pak Yat)
// ══════════════════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // BOOKINGS
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/manual', [BookingController::class, 'storeManual'])->name('bookings.manual.store');
    Route::post('/bookings/{booking}/confirm', [BookingController::class, 'confirmPayment'])->name('bookings.confirm');

    // EVENTS
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/plotting', [EventController::class, 'plotting'])->name('events.plotting');
    Route::post('/events/{event}/plotting', [EventController::class, 'storePlotting'])->name('events.plotting.store');

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
});

// ══════════════════════════════════════════════════════════════════════════
// 🎭 2. PERSONNEL ROUTES (Kru & Penari)
// ══════════════════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:personel'])->prefix('personnel')->name('personnel.')->group(function () {
    Route::get('/dashboard', function () {
        return view('personnel.dashboard');
    })->name('dashboard');

    Route::post('/events/{event}/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check_in');
});

// ══════════════════════════════════════════════════════════════════════════
// 🤝 3. KLIEN ROUTES (Penyewa Event)
// ══════════════════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:klien'])->prefix('klien')->name('klien.')->group(function () {
    Route::get('/dashboard', function () {
        return view('klien.dashboard');
    })->name('dashboard');
});

// Memuat Rute Login/Register yang dibuat oleh Breeze
require __DIR__ . '/auth.php';
