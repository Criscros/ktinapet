<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\UploadController;

// Public API endpoint for creating bookings (no CSRF, API middleware/prefix applies)
Route::post('bookings', [BookingController::class, 'store'])->name('api.bookings.store');

// TinyMCE image upload endpoint (expects { location: url } JSON)
Route::middleware('auth:sanctum')->post('uploads', [UploadController::class, 'store'])->name('api.uploads.store');
