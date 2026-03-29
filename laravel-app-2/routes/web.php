<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\RehearsalController;
use App\Http\Controllers\Admin\CancellationController;
use App\Http\Controllers\Admin\CostumeController;
use App\Http\Controllers\Admin\FinancialController;
use App\Http\Controllers\Personnel\AttendanceController;

Route::get('/', function () {
    return view('welcome'); // Landing Page Homepage
});

// Middleware autentikasi standar (Profil, dll)
Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});

// ══════════════════════════════════════════════════════════════════════════
// 👑 1. ADMIN ROUTES (Gudang Logika Pak Yat)
// ══════════════════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dasar
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Module: BOOKINGS & PROFIT LOCKING
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/manual', [BookingController::class, 'storeManual'])->name('bookings.manual.store'); // Quick Entry Admin
    Route::post('/bookings/{booking}/confirm', [BookingController::class, 'confirmPayment'])->name('bookings.confirm');

    // Module: SMART PLOTTING & TABRAKAN JADWAL
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/plotting', [EventController::class, 'plotting'])->name('events.plotting');
    Route::post('/events/{event}/plotting', [EventController::class, 'storePlotting'])->name('events.plotting.store');

    // Module: REHEARSALS (3-STAGES)
    Route::post('/events/{event}/rehearsals', [RehearsalController::class, 'store'])->name('rehearsals.store');

    // Module: CANCELLATIONS (Policy via SQL Function)
    Route::post('/bookings/{booking}/cancel', [CancellationController::class, 'store'])->name('bookings.cancel');

    // Module: COSTUMES (Asset & Rental)
    Route::post('/costume-usages/{usage}/return', [CostumeController::class, 'returnSanggarCostume'])->name('costumes.usage.return');
    Route::post('/costume-rentals/{rental}/return', [CostumeController::class, 'returnVendorRental'])->name('costumes.rental.return');

    // Module: FINANCIAL AUDITS
    Route::post('/financials/operational-costs/{cost}', [FinancialController::class, 'updateOperationalCost'])->name('financials.operational_costs.update');
});


// ══════════════════════════════════════════════════════════════════════════
// 🎭 2. PERSONNEL ROUTES (Kru & Penari)
// ══════════════════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:personel'])->prefix('personnel')->name('personnel.')->group(function () {

    Route::get('/dashboard', function () {
        return view('personnel.dashboard');
    })->name('dashboard');

    // Module: GHOSTING GUARD (Live Check-In Absensi)
    Route::post('/events/{event}/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check_in');
});


// ══════════════════════════════════════════════════════════════════════════
// 🤝 3. KLIEN ROUTES (Penyewa Event)
// ══════════════════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:klien'])->prefix('klien')->name('klien.')->group(function () {

    Route::get('/dashboard', function () {
        return view('klien.dashboard');
    })->name('dashboard');
    // Create Bookings, View History, Send Rating/Feedback...

});

// Load auth.php milik Breeze (Abaikan error jika belum ada, akan di-_scaffold_ user nanti)
if (file_exists(__DIR__ . '/auth.php')) {
    require __DIR__ . '/auth.php';
}
