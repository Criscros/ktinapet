<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\UploadController;
use App\Models\Pet;

// Public API endpoint for creating bookings (no CSRF, API middleware/prefix applies)
Route::post('bookings', [BookingController::class, 'store'])->name('api.bookings.store');

// TinyMCE image upload endpoint (expects { location: url } JSON)
Route::middleware('auth:sanctum')->post('uploads', [UploadController::class, 'store'])->name('api.uploads.store');

// Get pet details
Route::get('pets/{pet}', function (Pet $pet) {
    return response()->json([
        'id' => $pet->id,
        'name' => $pet->name,
        'type' => $pet->type,
        'breed' => $pet->breed,
        'weight' => $pet->weight,
        'coat' => $pet->coat,
        'customer_id' => $pet->customer_id,
    ]);
})->name('api.pets.show');
