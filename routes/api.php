<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\CardController;

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

// Public routes (no authentication required)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/verify-email', [AuthController::class, 'verifyEmail']);

// Protected routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Profile Management
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::put('/', [ProfileController::class, 'update']);
        Route::post('/change-password', [ProfileController::class, 'changePassword']);
        Route::post('/change-email', [ProfileController::class, 'changeEmail']);
        Route::post('/verify-email-change', [ProfileController::class, 'verifyEmailChange']);
        Route::get('/stats', [ProfileController::class, 'stats']);
        Route::get('/summary', [ProfileController::class, 'summary']);
    });

    // Payments
    Route::apiResource('payments', PaymentController::class)->names('api.payments');
    Route::post('payments/{payment}/refund', [PaymentController::class, 'refund'])->name('api.payments.refund');
    
    // Cards
    Route::apiResource('cards', CardController::class)->names('api.cards');
    Route::get('cards/search', [CardController::class, 'search']);
    Route::post('cards/{card}/set-default', [CardController::class, 'setDefault']);

    // Notification routes
    Route::prefix('notifications')->group(function () {
        // Get all notifications
        Route::get('/', [\App\Http\Controllers\Api\NotificationController::class, 'index']);
        
        // Get unread count
        Route::get('/unread-count', [\App\Http\Controllers\Api\NotificationController::class, 'unreadCount']);
        
        // Mark as read
        Route::post('/{notification}/read', [\App\Http\Controllers\Api\NotificationController::class, 'markAsRead']);
        
        // Mark all as read
        Route::post('/mark-all-read', [\App\Http\Controllers\Api\NotificationController::class, 'markAllAsRead']);
    });
});

// API Documentation
Route::get('/docs', function () {
    return view('api-docs');
})->name('api.docs');

// Swagger UI Documentation
Route::get('/swagger', function () {
    return response()->file(public_path('api-docs/swagger-ui.html'));
})->name('api.swagger');

// Swagger JSON file
Route::get('/swagger.json', function () {
    return response()->file(public_path('api-docs/swagger.json'));
})->name('api.swagger.json');
