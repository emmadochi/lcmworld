<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AppManagerController;

use App\Http\Controllers\Admin\AnalyticsController;

use App\Http\Controllers\AdminAuthController;

Route::get('/', function () {
    return view('landing');
});

// Admin Auth Routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::match(['get', 'post'], '/admin/logout', [AdminAuthController::class, 'logout'])->name('logout');

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', function () { return redirect('/admin/dashboard'); });
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/apps', [AppManagerController::class, 'index']);
    Route::post('/apps/upload', [AppManagerController::class, 'upload']);
    Route::post('/apps/{id}/update', [AppManagerController::class, 'update']);
    Route::post('/apps/{id}/toggle-featured', [AppManagerController::class, 'toggleFeatured']);
    Route::delete('/apps/{id}', [AppManagerController::class, 'destroy']);
    Route::get('/analytics', [AnalyticsController::class, 'index']);
    
    // Users Management
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::delete('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');
    
    // Categories
    Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index']);
    Route::post('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store']);
    Route::post('/categories/{id}/update', [\App\Http\Controllers\Admin\CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy']);

    // Documents
    Route::get('/documents', [\App\Http\Controllers\Admin\DocumentController::class, 'index']);
    Route::post('/documents/upload', [\App\Http\Controllers\Admin\DocumentController::class, 'upload']);
    Route::delete('/documents/{id}', [\App\Http\Controllers\Admin\DocumentController::class, 'destroy']);

    // Beta Feedback & Product Intelligence
    Route::get('/beta-feedback', [\App\Http\Controllers\Admin\BetaFeedbackController::class, 'index']);
    Route::get('/beta-feedback/investor-report', [\App\Http\Controllers\Admin\BetaFeedbackController::class, 'investorReport']);
    Route::get('/beta-feedback/export-csv', [\App\Http\Controllers\Admin\BetaFeedbackController::class, 'exportCsv']);
    Route::get('/beta-feedback/seed-samples', [\App\Http\Controllers\Admin\BetaFeedbackController::class, 'seedSamples']);
    Route::post('/beta-feedback/{id}/status', [\App\Http\Controllers\Admin\BetaFeedbackController::class, 'updateStatus']);
    Route::delete('/beta-feedback/{id}', [\App\Http\Controllers\Admin\BetaFeedbackController::class, 'destroy']);

    // Cache clearing and auto-migration utility
    Route::get('/clear-cache', function () {
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        } catch (\Exception $e) {}
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        return redirect('/admin/apps')->with('success', 'Server caches cleared and database updated successfully!');
    });
});

// Direct storage fallback route to serve public files cleanly
Route::get('/storage/{folder}/{filename}', function ($folder, $filename) {
    $path = storage_path("app/public/{$folder}/{$filename}");
    if (!file_exists($path)) {
        $path = public_path("storage/{$folder}/{$filename}");
    }
    if (!file_exists($path) || is_dir($path)) {
        abort(404, 'File not found');
    }
    return response()->file($path);
})->where('filename', '.*');

// Digital Asset Links for Android App Links verification
Route::get('/.well-known/assetlinks.json', function () {
    $path = public_path('.well-known/assetlinks.json');
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path, ['Content-Type' => 'application/json']);
});
