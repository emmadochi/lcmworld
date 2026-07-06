<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MobileAppController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\DocumentController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::prefix('admin')->group(function () {
    Route::get('/apps', [MobileAppController::class, 'index']);
    Route::get('/apps/featured', [MobileAppController::class, 'featured']);
    Route::get('/categories', [MobileAppController::class, 'categories']);
    Route::post('/apps/{id}/track', [MobileAppController::class, 'track']);
    
    // Reviews
    Route::get('/apps/{id}/reviews', [ReviewController::class, 'index']);
    Route::post('/apps/{id}/reviews', [ReviewController::class, 'store'])->middleware('auth:sanctum');

    // Documents
    Route::get('/documents', [DocumentController::class, 'index']);
});
