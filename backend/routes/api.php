<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DonorController;
use App\Http\Controllers\Api\DonationController;
use App\Http\Controllers\Api\DashboardController; // Jika DashboardController juga ditaruh di folder Api

// Dapat diakses via: GET http://localhost:8000/api/categories
// Route::get('categories', CategoryController::class);

// Route::get('/dashboard', [DashboardController::class, 'index']);
// Route::apiResource('donors', DonorController::class);
// Route::apiResource('donations', DonationController::class);

// Public Route
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes (Harus Mengirimkan Header Bearer Token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    // Kamu bisa memasukkan rute donatur & donasi ke sini jika ingin di-protect login:
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::apiResource('donors', DonorController::class);
    Route::apiResource('donations', DonationController::class);
});