<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController; 
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;

Route::post('login', [AuthController::class, 'login']);
Route::post('users', [AuthController::class, 'register'])->name('register');
Route::post('verify-email', [AuthController::class, 'verifyEmail']);
Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('reset-password', [AuthController::class, 'updatePassword']);





Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::delete('users/{id}', [AuthController::class, 'deleteUsers']);
    Route::get('users', [AuthController::class, 'users']);
    Route::get('users/{id}', [AuthController::class, 'userData']); 
    Route::put('users/{id}', [AuthController::class, 'updateUser']);
    Route::get('profile', [AuthController::class, 'profileData']);
    Route::put('profile', [AuthController::class, 'updateProfile']);
    Route::patch('changePassword', [AuthController::class, 'changePassword']);
    Route::get('logout', [AuthController::class, 'logout']);

    // Product CRUD
    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'create']);
    Route::put('products/{id}', [ProductController::class, 'update']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::delete('products/{product}', [ProductController::class, 'destroy']);
    Route::post('products/file', [ProductController::class, 'storeFile']);
    
      // Booking CRUD
      Route::post('booking', [BookingController::class, 'store']);
      Route::put('booking/{id}', [BookingController::class, 'update']);
      Route::get('booking/{id}', [BookingController::class, 'show']);
      Route::get('booking', [BookingController::class, 'index']);
      Route::get('mybooking', [BookingController::class, 'getUserBookings']);
      Route::get('booking/user/{id}', [BookingController::class, 'getIndUserBooking']);



      
   // Booking Item CRUD
   Route::post('/booking-items', [BookingItemController::class, 'store']);
      Route::get('booking/item/{id}', [BookingItemController::class, 'show']);
      Route::put('booking/item/{id}', [BookingItemController::class, 'update']);
      Route::delete('booking/item/{id}', [BookingItemController::class, 'destroy']);

      
      
      Route::get('products/stocks/{product_id}', [StockController::class, 'productStock']);
      Route::get('users/stocks/{user_id}', [StockController::class, 'userStock']);     
});


