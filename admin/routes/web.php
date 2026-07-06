<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AppManagerController;

use App\Http\Controllers\Admin\AnalyticsController;

use App\Http\Controllers\AdminAuthController;

Route::get('/', function () {
    return redirect('/admin/dashboard');
});

// Admin Auth Routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', function () { return redirect('/admin/dashboard'); });
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/apps', [AppManagerController::class, 'index']);
    Route::post('/apps/upload', [AppManagerController::class, 'upload']);
    Route::get('/analytics', [AnalyticsController::class, 'index']);
    
    // Categories
    Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index']);
    Route::post('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store']);
    Route::delete('/categories/{id}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy']);

    // Documents
    Route::get('/documents', [\App\Http\Controllers\Admin\DocumentController::class, 'index']);
    Route::post('/documents/upload', [\App\Http\Controllers\Admin\DocumentController::class, 'upload']);
    Route::delete('/documents/{id}', [\App\Http\Controllers\Admin\DocumentController::class, 'destroy']);
});
