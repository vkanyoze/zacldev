<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\CardsController;
use App\Http\Controllers\WebhookController;

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
Route::get('dashboard', [CustomAuthController::class, 'dashboard'])->name('dashboards'); 
Route::get('login', [CustomAuthController::class, 'index'])->name('login');
Route::get('forgot', [CustomAuthController::class, 'forgot'])->name('forgot');
Route::post('custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom'); 
Route::get('registration', [CustomAuthController::class, 'registration'])->name('register-user');
Route::post('custom-registration', [CustomAuthController::class, 'customRegistration'])->name('register.custom'); 
Route::get('signout', [CustomAuthController::class, 'signOut'])->name('signout');
Route::get('account/verify/{token}', [CustomAuthController::class, 'verifyAccount'])->name('user.verify'); 
Route::middleware('auth')->get('account/update/', [CustomAuthController::class, 'updateEmail'])->name('user.update'); 
Route::middleware('auth')->get('account/passwords/', [CustomAuthController::class, 'updatePassword'])->name('user.password'); 
Route::middleware('auth')->post('account/email/', [CustomAuthController::class, 'changeEmail'])->name('user.email'); 
Route::middleware('auth')->post('account/change/', [CustomAuthController::class, 'changePassword'])->name('password.change');
Route::post('/forgot-password', [CustomAuthController::class, 'resetPasswordLink'])->middleware('guest')->name('password.request');
Route::get('reset-password/{token}', [CustomAuthController::class, 'showResetPasswordForm'])->name('reset.password.get');
Route::post('reset-password', [CustomAuthController::class, 'submitResetPasswordForm'])->name('reset.password.post');
Route::get('reset-email/{token}', [CustomAuthController::class, 'showResetEmailForm'])->name('reset.email.get');
Route::post('/resend-verification-email', [CustomAuthController::class, 'resendVerificationEmail'])->name('resend-verification-email');
Route::get('/resend', [CustomAuthController::class, 'resendEmail'])->name('resend');

Route::middleware('auth')->prefix('cards')->group(function () {
    Route::get('/', [CardsController::class, 'index'])->name('cards.index'); // show all cards
    Route::get('/create', [CardsController::class, 'create'])->name('cards.create'); // show card create form
    Route::post('/', [CardsController::class, 'store'])->name('cards.store'); // store newly created card
    Route::get('/edit/{id}', [CardsController::class, 'edit'])->name('cards.edit'); // show card edit form
    Route::post('/update/{id}', [CardsController::class, 'update'])->name('cards.update'); // update edited card
    Route::delete('/{id}', [CardsController::class, 'destroy'])->name('cards.destroy'); // delete a card
    Route::get('/search', [CardsController::class, 'searchCards'])->name('cards.search'); // search for cards
    Route::get('/payment/{id}', [CardsController::class, 'payment'])->name('cards.payment'); // search for cards
    Route::get('checkout/', [CardsController::class, 'checkout'])->name('cards.checkout'); // store newly created payments
    Route::get('/select', [CardsController::class, 'select'])->name('cards.select'); // store newly created payments
    Route::get('/cvv', [CardsController::class, 'cvv'])->name('cards.cvv'); // store newly created payments
    Route::post('process/', [CardsController::class, 'process'])->name('cards.process'); 

});
Route::middleware('auth')->prefix('payments')->group(function () {
    Route::get('/', [PaymentsController::class, 'index'])->name('payments.index'); // show all payments
    Route::get('/create', [PaymentsController::class, 'create'])->name('payments.create'); // show payments create form
    Route::post('/', [PaymentsController::class, 'store'])->name('payments.store'); // store newly created payments
    Route::get('/select', [PaymentsController::class, 'select'])->name('payments.card'); // store newly created payments
    Route::get('checkout/', [PaymentsController::class, 'checkout'])->name('payments.checkout'); // store newly created payments
    Route::post('process/', [PaymentsController::class, 'process'])->name('payments.process'); // store newly created payments
    Route::delete('/{id}', [PaymentsController::class, 'destroy'])->name('payments.destroy'); // delete a payments
    Route::get('/search', [PaymentsController::class, 'searchCards'])->name('payments.search'); // search for payments
    Route::post('invoice/{id}', [PaymentsController::class, 'invoice'])->name('payments.invoice');
});
Route::get('send-email-queue', function(){
    $details['email'] = '<EMAIL ADDRESS>';
    dispatch(new App\Jobs\TestEmailJob($details));
    return response()->json(['message'=>'Mail Send Successfully!!']);
});

Route::post('/webhook', [WebhookController::class, 'handleWebhook']);
//php artisan queue:listen
