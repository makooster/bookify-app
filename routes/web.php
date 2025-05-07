<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Главная страница
Route::get('/', [PropertyController::class, 'index'])->name('home');

// Настройка профиля
Route::middleware('auth')->group(function() {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Объекты размещения (жилье)
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/search', [PropertyController::class, 'search'])->name('properties.search');
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');

// Маршруты с защитой аутентификации
Route::middleware('auth')->group(function() {
    // Управление объектами размещения
    Route::get('/properties/create', [PropertyController::class, 'create'])->name('properties.create')->middleware('can:create,App\Models\Property');
    Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store');
    Route::get('/properties/{property}/edit', [PropertyController::class, 'edit'])->name('properties.edit');
    Route::put('/properties/{property}', [PropertyController::class, 'update'])->name('properties.update');
    Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('properties.destroy');

    // Маршрут для одобрения объекта (только для админа)
    Route::patch('/properties/{property}/approve', [PropertyController::class, 'approve'])->name('properties.approve');

    // Бронирования
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/properties/{property}/book', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/properties/{property}/book', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');

    // Отзывы
    Route::get('/bookings/{booking}/review', [ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/bookings/{booking}/review', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Административная панель
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/properties', [AdminController::class, 'properties'])->name('properties');
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews');

    // Управление удобствами
    Route::get('/amenities', [AdminController::class, 'amenities'])->name('amenities');
    Route::get('/amenities/create', [AdminController::class, 'createAmenity'])->name('amenities.create');
    Route::post('/amenities', [AdminController::class, 'storeAmenity'])->name('amenities.store');
    Route::get('/amenities/{amenity}/edit', [AdminController::class, 'editAmenity'])->name('amenities.edit');
    Route::put('/amenities/{amenity}', [AdminController::class, 'updateAmenity'])->name('amenities.update');
    Route::delete('/amenities/{amenity}', [AdminController::class, 'destroyAmenity'])->name('amenities.destroy');
});
