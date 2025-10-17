<?php

  use Illuminate\Support\Facades\Route;
  use Inertia\Inertia;
  use App\Http\Controllers\BookingController;
  use App\Http\Controllers\BlogPostController;
 
  Route::get('/', function () {
      return Inertia::render('Welcome');
  })->name('home');
 
  Route::get('dashboard', function () {
      return Inertia::render('Dashboard');
  })->middleware(['auth', 'verified'])->name('dashboard');

  Route::get('bookings', [BookingController::class, 'index'])
      ->middleware(['auth', 'verified'])
      ->name('bookings');
 
  Route::patch('bookings/{booking}/status', [BookingController::class, 'updateStatus'])
      ->middleware(['auth', 'verified'])
      ->name('bookings.status');
 
  Route::patch('bookings/{booking}/notes', [BookingController::class, 'updateNotes'])
      ->middleware(['auth', 'verified'])
      ->name('bookings.notes');
 
  // Blog routes (explicit, chained style)
  Route::get('blog', [BlogPostController::class, 'index'])
      ->middleware(['auth', 'verified'])
      ->name('blog.index');
 
  Route::get('blog/create', [BlogPostController::class, 'create'])
      ->middleware(['auth', 'verified'])
      ->name('blog.create');
 
  Route::post('blog', [BlogPostController::class, 'store'])
      ->middleware(['auth', 'verified'])
      ->name('blog.store');
 
  Route::get('blog/{blog}/edit', [BlogPostController::class, 'edit'])
      ->middleware(['auth', 'verified'])
      ->name('blog.edit');
 
  Route::put('blog/{blog}', [BlogPostController::class, 'update'])
      ->middleware(['auth', 'verified'])
      ->name('blog.update');
 
  Route::delete('blog/{blog}', [BlogPostController::class, 'destroy'])
      ->middleware(['auth', 'verified'])
      ->name('blog.destroy');
 
  require __DIR__.'/settings.php';
  require __DIR__.'/auth.php';
