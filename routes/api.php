<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes
// Route::post('/login', 'App\Http\Controllers\API\AuthController@login');
// Route::post('/register', 'App\Http\Controllers\API\AuthController@register');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    // Route::post('/logout', 'App\Http\Controllers\API\AuthController@logout');
    // Route::get('/user', 'App\Http\Controllers\API\AuthController@user');

    // Payments
    Route::apiResource('payments', PaymentController::class);
    Route::post('payments/{payment}/refund', [PaymentController::class, 'refund'])->name('payments.refund');
    
    // Cards
    // Route::apiResource('cards', 'App\Http\Controllers\API\CardController');

    // Notification routes
    Route::middleware(['auth:sanctum'])->group(function () {
        // Get all notifications
        Route::get('/notifications', [\App\Http\Controllers\Api\NotificationController::class, 'index']);
        
        // Get unread count
        Route::get('/notifications/unread-count', [\App\Http\Controllers\Api\NotificationController::class, 'unreadCount']);
        
        // Mark as read
        Route::post('/notifications/{notification}/read', [\App\Http\Controllers\Api\NotificationController::class, 'markAsRead']);
        
        // Mark all as read
        Route::post('/notifications/mark-all-read', [\App\Http\Controllers\Api\NotificationController::class, 'markAllAsRead']);
    });
});

// API Documentation
Route::get('/docs', function () {
    return view('api-docs');
})->name('api.docs');
