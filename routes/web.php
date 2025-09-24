<?php

use Illuminate\Support\Facades\Route;
// Các use Livewire Pages đã có
use App\Livewire\Pages\HomePage;
use App\Livewire\Pages\HotelList;
use App\Livewire\Pages\HotelDetail;
use App\Livewire\Pages\BookingProcess;
use App\Livewire\Pages\UserDashboard;
use App\Livewire\Pages\BlogPage;
use App\Livewire\Pages\BlogDetail;
use App\Livewire\Pages\BookingConfirmation;
use App\Livewire\Components\UserBookings; 
use App\Livewire\Components\UserReviews;  
use App\Livewire\Components\UserProfile;  
use App\Livewire\Pages\Auth\Login;
use App\Livewire\Pages\Auth\Register;

// --- Public Routes ---
Route::get('/', HomePage::class)->name('home');
Route::get('/hotels', HotelList::class)->name('hotels.list');
Route::get('/hotels/{id}', HotelDetail::class)->name('hotels.detail');
Route::get('/blog', BlogPage::class)->name('blog.list');
Route::get('/blog/{slug}', BlogDetail::class)->name('blog.detail');

// --- Guest Routes ---
Route::middleware('guest')->group(function () {
    Route::get('login', Login::class)->name('login');
    Route::get('register', Register::class)->name('register');
});

// --- Auth Routes ---
require __DIR__.'/auth.php';

// --- Protected Routes ---
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', UserDashboard::class)->name('dashboard'); // Trang dashboard chính

    // Booking Process
    Route::get('/booking/create/{hotelId}/{roomId?}', BookingProcess::class)->name('booking.create');
    Route::get('/booking/confirmation/{booking}', BookingConfirmation::class)->name('booking.confirmation');

    Route::prefix('user')->name('user.')->group(function() {
        Route::get('/dashboard', UserDashboard::class)->name('dashboard'); 
        Route::get('/bookings', UserBookings::class)->name('bookings');
        Route::get('/reviews', UserReviews::class)->name('reviews');
        Route::get('/profile', [UserProfile::class, '__invoke'])->name('profile.edit');

    });

});