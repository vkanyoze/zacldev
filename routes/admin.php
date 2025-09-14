<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NotificationController;
use Illuminate\Support\Facades\Route;

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes (not authenticated)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
    });

    // Authenticated admin routes
    Route::middleware(['auth:admin', 'admin'])->group(function () {
        // Logout
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        
        
        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [DashboardController::class, 'users'])->name('index');
            Route::get('/{user}', [DashboardController::class, 'showUser'])->name('show');
            Route::get('/{user}/edit', [DashboardController::class, 'editUser'])->name('edit');
            Route::put('/{user}', [DashboardController::class, 'updateUser'])->name('update');
            Route::delete('/{user}', [DashboardController::class, 'destroyUser'])->name('destroy');
            Route::put('/{user}/status', [DashboardController::class, 'updateUserStatus'])->name('status.update');
            Route::patch('/{user}/unsuspend', [DashboardController::class, 'unsuspendUser'])->name('unsuspend');
            
            // Two-factor authentication
            Route::delete('/{user}/two-factor-authentication', [DashboardController::class, 'destroyTwoFactor'])
                ->name('two-factor.destroy');
        });

        // Payment Management
        Route::prefix('payments')->name('payments.')->group(function () {
            Route::get('/', [DashboardController::class, 'payments'])->name('index');
            Route::get('/{payment}', [DashboardController::class, 'showPayment'])->name('show');
            Route::get('/data', [DashboardController::class, 'paymentsDataTable'])->name('datatable');
            Route::get('/export', [DashboardController::class, 'exportPayments'])->name('export');
            Route::post('/{payment}/refund', [DashboardController::class, 'refundPayment'])->name('refund');
        });

        // System Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [DashboardController::class, 'settings'])->name('index');
            Route::post('/', [DashboardController::class, 'updateSettings'])->name('update');
        });

        // Notifications
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::get('/unread', [NotificationController::class, 'unread'])->name('unread');
            Route::post('/{notification}/read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
            Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
            Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('destroy');
            Route::delete('/', [NotificationController::class, 'clearAll'])->name('clearAll');
            Route::get('/stats', [NotificationController::class, 'stats'])->name('stats');
            
            // Notification preferences
            Route::get('/preferences', [NotificationController::class, 'preferences'])->name('preferences');
            Route::put('/preferences', [NotificationController::class, 'updatePreferences'])->name('updatePreferences');
        });

        // Profile
        Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
        Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    });
});
