<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Публичные API
Route::get('/properties', [PropertyController::class, 'apiIndex']);
Route::get('/properties/{property}', [PropertyController::class, 'apiShow']);
Route::get('/properties/{propertyId}/reviews', [ReviewController::class, 'apiPropertyReviews']);

// API с защитой аутентификации
Route::middleware('auth:sanctum')->group(function() {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/bookings', [BookingController::class, 'apiIndex']);
    Route::get('/bookings/{booking}', [BookingController::class, 'apiShow']);
});
