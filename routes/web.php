<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Member\AuthController;
use App\Http\Controllers\Member\DashboardController;
use App\Http\Controllers\Member\ProfileController;
use App\Http\Controllers\Member\PaymentController;

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
    return view('welcome');
});

// Routes d'enregistrement public
Route::prefix('register')->name('public.registration.')->group(function () {
    Route::get('/', [App\Http\Controllers\Public\RegistrationController::class, 'showRegistrationForm'])->name('form');
    Route::post('/', [App\Http\Controllers\Public\RegistrationController::class, 'register'])->name('register');
    Route::get('/success', [App\Http\Controllers\Public\RegistrationController::class, 'success'])->name('success');
    Route::post('/check-code', [App\Http\Controllers\Public\RegistrationController::class, 'checkSponsorshipCode'])->name('check-code');
});

// Routes pour l'espace membre
Route::prefix('member')->name('member.')->group(function () {
    // Routes publiques (sans authentification)
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    
    // Routes protégées (avec authentification membre)
    Route::middleware('auth.member')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('profile', [ProfileController::class, 'index'])->name('profile');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('payments', [PaymentController::class, 'index'])->name('payments');
        Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
        Route::get('membership', [DashboardController::class, 'membership'])->name('membership');
        
        // Routes de paiement
        Route::get('payment/adhesion', [App\Http\Controllers\Member\PaymentProcessingController::class, 'showAdhesionPayment'])->name('payment.adhesion');
        Route::get('payment/contribution', [App\Http\Controllers\Member\PaymentProcessingController::class, 'showContributionPayment'])->name('payment.contribution');
        Route::post('payment/adhesion', [App\Http\Controllers\Member\PaymentProcessingController::class, 'processAdhesionPayment'])->name('payment.adhesion.process');
        Route::post('payment/contribution', [App\Http\Controllers\Member\PaymentProcessingController::class, 'processContributionPayment'])->name('payment.contribution.process');
        Route::post('payment/confirm', [App\Http\Controllers\Member\PaymentProcessingController::class, 'confirmPayment'])->name('payment.confirm');
        
        // Routes de parrainage
        Route::get('sponsorship', [App\Http\Controllers\Member\SponsorshipController::class, 'index'])->name('sponsorship.index');
        Route::get('sponsorship/create', [App\Http\Controllers\Member\SponsorshipController::class, 'create'])->name('sponsorship.create');
        Route::post('sponsorship', [App\Http\Controllers\Member\SponsorshipController::class, 'store'])->name('sponsorship.store');
        Route::get('sponsorship/{sponsorship}', [App\Http\Controllers\Member\SponsorshipController::class, 'show'])->name('sponsorship.show');
        Route::post('sponsorship/{sponsorship}/complete', [App\Http\Controllers\Member\SponsorshipController::class, 'complete'])->name('sponsorship.complete');
        Route::delete('sponsorship/{sponsorship}', [App\Http\Controllers\Member\SponsorshipController::class, 'destroy'])->name('sponsorship.destroy');
        Route::post('sponsorship/check-code', [App\Http\Controllers\Member\SponsorshipController::class, 'checkCode'])->name('sponsorship.check-code');
    });
});

require __DIR__.'/auth.php';

// Webhook Stripe (route publique)
Route::post('webhook/stripe', [App\Http\Controllers\Member\PaymentProcessingController::class, 'webhook'])->name('webhook.stripe');
