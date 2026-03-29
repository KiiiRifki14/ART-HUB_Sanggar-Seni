<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\RehearsalController;
use App\Http\Controllers\Admin\CancellationController;
use App\Http\Controllers\Admin\CostumeController;
use App\Http\Controllers\Admin\FinancialController;
use App\Http\Controllers\Personnel\AttendanceController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('login');
});

// 👇 PENGATUR LALU LINTAS ROLE
Route::get('/dashboard', function () {
    // Ubah baris di bawah ini 👇
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

    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/manual', [BookingController::class, 'storeManual'])->name('bookings.manual.store');
    Route::post('/bookings/{booking}/confirm', [BookingController::class, 'confirmPayment'])->name('bookings.confirm');

    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{event}/plotting', [EventController::class, 'plotting'])->name('events.plotting');
    Route::post('/events/{event}/plotting', [EventController::class, 'storePlotting'])->name('events.plotting.store');

    Route::post('/events/{event}/rehearsals', [RehearsalController::class, 'store'])->name('rehearsals.store');
    Route::post('/bookings/{booking}/cancel', [CancellationController::class, 'store'])->name('bookings.cancel');

    Route::post('/costume-usages/{usage}/return', [CostumeController::class, 'returnSanggarCostume'])->name('costumes.usage.return');
    Route::post('/costume-rentals/{rental}/return', [CostumeController::class, 'returnVendorRental'])->name('costumes.rental.return');

    Route::post('/financials/operational-costs/{cost}', [FinancialController::class, 'updateOperationalCost'])->name('financials.operational_costs.update');
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
