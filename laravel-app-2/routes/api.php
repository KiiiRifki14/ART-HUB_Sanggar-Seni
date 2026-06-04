<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GeocodingController;

/**
 * PUBLIC API ROUTES (NO AUTHENTICATION)
 * Used for maps functionality accessible from any view
 */

Route::prefix('geocoding')->group(function () {
    // Search addresses by query string
    Route::get('/search', [GeocodingController::class, 'searchAddress'])->name('geocoding.search');

    // Get address from coordinates (reverse geocoding)
    Route::get('/reverse', [GeocodingController::class, 'reverseGeocode'])->name('geocoding.reverse');

    // Autocomplete suggestions
    Route::get('/autocomplete', [GeocodingController::class, 'autocomplete'])->name('geocoding.autocomplete');
});
