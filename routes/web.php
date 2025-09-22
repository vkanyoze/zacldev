<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\CardsController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\SocialAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $title = 'login';
    $subHeading = 'Access your account and manage your payments conveniently <br> and securely.';
    return view('sign-in', compact('title', 'subHeading'));
});

Route::get('/debug-analytics', function () {
    return view('debug-analytics');
});
Route::get('dashboard', [CustomAuthController::class, 'dashboard'])->name('dashboards')->middleware(['auth', '2fa']); 
Route::get('login', [CustomAuthController::class, 'index'])->name('login');
Route::get('forgot', [CustomAuthController::class, 'forgot'])->name('forgot');
Route::post('custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom'); 
Route::get('registration', [CustomAuthController::class, 'registration'])->name('register-user');
Route::post('custom-registration', [CustomAuthController::class, 'customRegistration'])->name('register.custom'); 
Route::get('signout', [CustomAuthController::class, 'signOut'])->name('signout');
Route::get('account/verify/{token}', [CustomAuthController::class, 'verifyAccount'])->name('user.verify'); 
Route::middleware(['auth', '2fa'])->get('account/update/', [CustomAuthController::class, 'updateEmail'])->name('user.update'); 
Route::middleware(['auth', '2fa'])->get('account/passwords/', [CustomAuthController::class, 'updatePassword'])->name('user.password'); 
Route::middleware(['auth', '2fa'])->post('account/email/', [CustomAuthController::class, 'changeEmail'])->name('user.email'); 
Route::middleware(['auth', '2fa'])->post('account/change/', [CustomAuthController::class, 'changePassword'])->name('password.change');
Route::post('/forgot-password', [CustomAuthController::class, 'resetPasswordLink'])->middleware('guest')->name('password.request');
Route::get('reset-password/{token}', [CustomAuthController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [CustomAuthController::class, 'submitResetPasswordForm'])->name('reset.password.post');
Route::get('reset-email/{token}', [CustomAuthController::class, 'showResetEmailForm'])->name('reset.email.get');
Route::post('/resend-verification-email', [CustomAuthController::class, 'resendVerificationEmail'])->name('resend-verification-email');
Route::get('/resend', [CustomAuthController::class, 'resendEmail'])->name('resend');

// Social Login Routes
Route::get('/auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
Route::get('/auth/facebook', [SocialAuthController::class, 'redirectToFacebook'])->name('auth.facebook');
Route::get('/auth/facebook/callback', [SocialAuthController::class, 'handleFacebookCallback'])->name('auth.facebook.callback');

// Two-Factor Authentication Routes
Route::middleware('auth')->group(function () {
    Route::get('/2fa/verify', [App\Http\Controllers\TwoFactorAuthController::class, 'showVerificationForm'])->name('2fa.verify');
    Route::post('/2fa/verify', [App\Http\Controllers\TwoFactorAuthController::class, 'verifyCode'])->name('2fa.verify');
    Route::post('/2fa/send', [App\Http\Controllers\TwoFactorAuthController::class, 'sendCode'])->name('2fa.send');
    Route::post('/2fa/resend', [App\Http\Controllers\TwoFactorAuthController::class, 'resendCode'])->name('2fa.resend');
    Route::get('/2fa/skip', [App\Http\Controllers\TwoFactorAuthController::class, 'skip2FA'])->name('2fa.skip');
});

// Card routes removed - card functionality integrated into payment flow
Route::middleware(['auth', '2fa'])->prefix('payments')->group(function () {
    Route::get('/', [PaymentsController::class, 'index'])->name('payments.index'); // show all payments
    Route::post('/export', [PaymentsController::class, 'export'])->name('payments.export'); // export payments as CSV
    Route::get('/create', [PaymentsController::class, 'create'])->name('payments.create'); // show payments create form
    Route::post('/', [PaymentsController::class, 'store'])->name('payments.store'); // store newly created payments
    Route::get('/select', [PaymentsController::class, 'select'])->name('payments.card'); // show card details form
    Route::post('process/', [PaymentsController::class, 'process'])->name('payments.process'); // process payment with card details
    Route::delete('/{id}', [PaymentsController::class, 'destroy'])->name('payments.destroy'); // delete a payments
    Route::get('/search', [PaymentsController::class, 'searchCards'])->name('payments.search'); // search for payments
    Route::post('invoice/{id}', [PaymentsController::class, 'invoice'])->name('payments.invoice');
});

// Admin Auth Routes
Route::prefix('admin')->group(function () {
    // Existing admin routes...
    
    // Password Reset Routes
    Route::get('password/reset', [\App\Http\Controllers\Admin\ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('admin.password.request');
        
    Route::post('password/email', [\App\Http\Controllers\Admin\ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('admin.password.email');
        
    Route::get('password/reset/{token}', [\App\Http\Controllers\Admin\ResetPasswordController::class, 'showResetForm'])
        ->name('admin.password.reset');
        
    Route::post('password/reset', [\App\Http\Controllers\Admin\ResetPasswordController::class, 'reset'])
        ->name('admin.password.update');
});

Route::get('send-email-queue', function(){
    $details['email'] = '<EMAIL ADDRESS>';
    dispatch(new App\Jobs\TestEmailJob($details));
    return response()->json(['message'=>'Mail Send Successfully!!']);
});

Route::post('/webhook', [WebhookController::class, 'handleWebhook']);
//php artisan queue:listen

// Test notification route (only in local environment)
if (app()->environment('local')) {
    Route::get('/test-notification', function () {
        $admin = \App\Models\Admin::first();
        
        if (!$admin) {
            return 'No admin user found. Please create one first.';
        }
        
        // Send a test notification
        $admin->notify(new \App\Notifications\AdminNotification(
            'test',
            'Test Notification',
            'This is a test notification from the system.',
            [
                'url' => '/admin/dashboard',
                'icon' => 'bell'
            ]
        ));
        
        return 'Test notification sent to ' . $admin->email;
    });
}
