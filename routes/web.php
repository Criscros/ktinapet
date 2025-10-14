<?php

  use Illuminate\Support\Facades\Route;
  use Inertia\Inertia;
  use App\Http\Controllers\BookingController;
 
  Route::get('/', function () {
      return Inertia::render('Welcome');
  })->name('home');
 
  Route::get('dashboard', function () {
      return Inertia::render('Dashboard');
  })->middleware(['auth', 'verified'])->name('dashboard');

  Route::get('bookings', [BookingController::class, 'index'])
      ->middleware(['auth', 'verified'])
      ->name('bookings');
 
  require __DIR__.'/settings.php';
  require __DIR__.'/auth.php';
