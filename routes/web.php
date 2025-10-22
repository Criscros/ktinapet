<?php

  use Illuminate\Support\Facades\Route;
  use Inertia\Inertia;
  use App\Http\Controllers\BookingController;
  use App\Http\Controllers\MultimediaController;
  use App\Http\Controllers\S3Controller;
  use App\Http\Controllers\PostController;
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

  Route::get('customer/{customer}/pets', function (\App\Models\Customer $customer) {
      $pets = $customer->pets()->get()->map(function ($pet) {
          return [
              'id' => $pet->id,
              'name' => $pet->name,
              'type' => $pet->type,
              'breed' => $pet->breed,
              'weight' => $pet->weight,
              'coat' => $pet->coat,
              'created_at' => optional($pet->created_at)->format('Y-m-d H:i'),
          ];
      });

      return \Inertia\Inertia::render('CustomerPets', [
          'customer' => [
              'id' => $customer->id,
              'phone' => $customer->phone,
              'address' => $customer->address,
          ],
          'pets' => $pets,
      ]);
  })->middleware(['auth', 'verified'])->name('customer.pets');

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
        ->resource('multimedia', MultimediaController::class)
        ->names('multimedia');


    Route::middleware(['auth', 'verified'])->group(function () {
        Route::resource('posts', PostController::class)->names('posts');
        Route::get('admin/blogs', [PostController::class, 'index'])->name('posts.admin');
    });




  // Public list alias: /blogs
  Route::get('blogs', [PostController::class, 'publicIndex'])
      ->name('posts.public.alias');

  // Public show routes
  Route::get('blogs/{post}', [PostController::class, 'show'])
      ->name('posts.show');
  Route::get('bologs/{post}', [PostController::class, 'show'])
      ->name('posts.show.alias');

  // S3 presign (videos only)
  Route::get('/s3/presign', [S3Controller::class, 'presign'])
      ->middleware(['auth', 'verified'])
      ->name('s3.presign');

  require __DIR__.'/settings.php';
  require __DIR__.'/auth.php';
