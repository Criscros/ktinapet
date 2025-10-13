<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;

// Public API endpoint for creating bookings (no CSRF, API middleware/prefix applies)
Route::post('bookings', [BookingController::class, 'store'])->name('api.bookings.store');
