<?php

  use Illuminate\Support\Facades\Route;
  use Inertia\Inertia;
  use App\Http\Controllers\BookingController;
  use App\Http\Controllers\BlogPostController;
  use App\Http\Controllers\S3Controller;
  use App\Models\BlogPost;
 
  Route::get('/', function () {
      return Inertia::render('Welcome');
  })->name('home');

  // Public news page (serve posts with absolute S3 URLs)
  Route::get('/news', [BlogPostController::class, 'news'])->name('news.index');

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

  // S3 presign (videos only)
  Route::get('/s3/presign', [S3Controller::class, 'presign'])
      ->middleware(['auth', 'verified'])
      ->name('s3.presign');

  require __DIR__.'/settings.php';
  require __DIR__.'/auth.php';
