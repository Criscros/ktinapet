<?php

  use Illuminate\Support\Facades\Route;
  use Inertia\Inertia;
  use App\Http\Controllers\BookingController;
  use App\Http\Controllers\MultimediaController;
  use App\Http\Controllers\S3Controller;
  use App\Http\Controllers\TextBlogController;
  use App\Models\BlogPost;
 
  Route::get('/', function () {
      return Inertia::render('Welcome');
  })->name('home');

  // Public services page
  Route::get('/servicios', function () {
      return Inertia::render('services/Index');
  })->name('services.index');

  // Public about page
  Route::get('/nosotros', function () {
      return Inertia::render('about/Index');
  })->name('about.index');

  // Public contact page
  Route::get('/contactanos', function () {
      return Inertia::render('contact/Index');
  })->name('contact.index');

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
 

  // Public news page (serve posts with absolute S3 URLs)
  Route::get('/news', [MultimediaController::class, 'news'])->name('news.index');


  
  // Multimedia 
    Route::middleware(['auth', 'verified'])
        ->prefix('multimedia')
        ->name('multimedia.')
        ->group(function () {
            Route::get('/', [MultimediaController::class, 'index'])->name('index');
            Route::get('/create', [MultimediaController::class, 'create'])->name('create');
            Route::post('/', [MultimediaController::class, 'store'])->name('store');
            Route::get('/{multimedia}/edit', [MultimediaController::class, 'edit'])->name('edit');
            Route::put('/{multimedia}', [MultimediaController::class, 'update'])->name('update');
            Route::delete('/{multimedia}', [MultimediaController::class, 'destroy'])->name('destroy');
        });

  // Text blog create (TinyMCE)
  Route::get('textblog/create', [TextBlogController::class, 'create'])
      ->middleware(['auth', 'verified'])
      ->name('textblog.create');

  // Text blog admin CRUD
  Route::get('textblog', [TextBlogController::class, 'index'])
      ->middleware(['auth', 'verified'])
      ->name('textblog.index');
  Route::post('textblog', [TextBlogController::class, 'store'])
      ->middleware(['auth', 'verified'])
      ->name('textblog.store');
  Route::get('textblog/{textblog}/edit', [TextBlogController::class, 'edit'])
      ->middleware(['auth', 'verified'])
      ->name('textblog.edit');
  Route::put('textblog/{textblog}', [TextBlogController::class, 'update'])
      ->middleware(['auth', 'verified'])
      ->name('textblog.update');
  Route::delete('textblog/{textblog}', [TextBlogController::class, 'destroy'])
      ->middleware(['auth', 'verified'])
      ->name('textblog.destroy');

  // Public list (typo requested: /bologs)
  Route::get('bologs', [TextBlogController::class, 'publicIndex'])
      ->name('textblog.public');
  // Public list alias: /blogs
  Route::get('blogs', [TextBlogController::class, 'publicIndex'])
      ->name('textblog.public.alias');

  // Public show routes
  Route::get('blogs/{textblog}', [TextBlogController::class, 'show'])
      ->name('textblog.show');
  Route::get('bologs/{textblog}', [TextBlogController::class, 'show'])
      ->name('textblog.show.alias');

  // S3 presign (videos only)
  Route::get('/s3/presign', [S3Controller::class, 'presign'])
      ->middleware(['auth', 'verified'])
      ->name('s3.presign');

  require __DIR__.'/settings.php';
  require __DIR__.'/auth.php';
