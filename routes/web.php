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

// Public routes
Route::get('/', [App\Http\Controllers\Public\HomeController::class, 'index'])->name('public.home');
Route::get('/about', [App\Http\Controllers\Public\AboutController::class, 'index'])->name('public.about');
Route::get('/contact', [App\Http\Controllers\Public\ContactController::class, 'index'])->name('public.contact');
Route::post('/contact', [App\Http\Controllers\Public\ContactController::class, 'send'])->name('public.contact.send');

// Additional public pages
Route::get('/services', [App\Http\Controllers\Public\ServicesController::class, 'index'])->name('public.services');
Route::get('/contributions-deces', [App\Http\Controllers\Public\DeathContributionsController::class, 'index'])->name('public.death-contributions');
Route::get('/parrainage', [App\Http\Controllers\Public\SponsorshipController::class, 'index'])->name('public.sponsorship');
Route::get('/gestion-en-ligne', [App\Http\Controllers\Public\OnlineManagementController::class, 'index'])->name('public.online-management');
Route::get('/support-technique', [App\Http\Controllers\Public\TechnicalSupportController::class, 'index'])->name('public.technical-support');
Route::get('/faq', [App\Http\Controllers\Public\FAQController::class, 'index'])->name('public.faq');
// Published death events (public)
Route::get('/deces', [App\Http\Controllers\Public\DeathEventsController::class, 'index'])->name('public.death-events.index');
Route::get('/deces/{event}', [App\Http\Controllers\Public\DeathEventsController::class, 'show'])->name('public.death-events.show');

// Routes d'enregistrement public
Route::prefix('register')->name('public.registration.')->group(function () {
    Route::get('/', [App\Http\Controllers\Public\RegistrationController::class, 'showRegistrationForm'])->name('form');
    Route::post('/', [App\Http\Controllers\Public\RegistrationController::class, 'register'])->name('register');
    Route::get('/success', [App\Http\Controllers\Public\RegistrationController::class, 'success'])->name('success');
    Route::post('/check-code', [App\Http\Controllers\Public\RegistrationController::class, 'checkSponsorshipCode'])->name('check-code');
});

// Routes de réactivation
Route::prefix('reactivate')->name('public.reactivation.')->group(function () {
    Route::get('/', [App\Http\Controllers\Public\ReactivationController::class, 'showReactivationForm'])->name('form');
    Route::post('/', [App\Http\Controllers\Public\ReactivationController::class, 'reactivate'])->name('reactivate');
    Route::get('/success', [App\Http\Controllers\Public\ReactivationController::class, 'success'])->name('success');
    Route::post('/check-code', [App\Http\Controllers\Public\ReactivationController::class, 'checkLapsedCode'])->name('check-code');
});

// Routes d'inscription d'organisation
Route::prefix('organization-register')->name('public.organization-registration.')->group(function () {
    Route::get('/', [App\Http\Controllers\Public\OrganizationRegistrationController::class, 'showRegistrationForm'])->name('form');
    Route::post('/', [App\Http\Controllers\Public\OrganizationRegistrationController::class, 'register'])->name('register');
    Route::get('/success', [App\Http\Controllers\Public\OrganizationRegistrationController::class, 'success'])->name('success');
    Route::post('/check-code', [App\Http\Controllers\Public\OrganizationRegistrationController::class, 'checkSponsorshipCode'])->name('check-code');
});

// Routes de récupération de compte
Route::prefix('account-recovery')->name('public.account-recovery.')->group(function () {
    Route::get('/', [App\Http\Controllers\Public\AccountRecoveryController::class, 'showRecoveryForm'])->name('form');
    Route::post('/request', [App\Http\Controllers\Public\AccountRecoveryController::class, 'requestRecovery'])->name('request');
    Route::get('/sent', [App\Http\Controllers\Public\AccountRecoveryController::class, 'sent'])->name('sent');
    Route::get('/reset/{token}', [App\Http\Controllers\Public\AccountRecoveryController::class, 'showResetForm'])->name('reset');
    Route::post('/reset/{token}', [App\Http\Controllers\Public\AccountRecoveryController::class, 'resetPin'])->name('reset-pin');
    Route::get('/success', [App\Http\Controllers\Public\AccountRecoveryController::class, 'success'])->name('success');
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
        
        // Routes de documents
        Route::get('documents', [App\Http\Controllers\Member\DocumentController::class, 'index'])->name('documents.index');
        Route::get('documents/create', [App\Http\Controllers\Member\DocumentController::class, 'create'])->name('documents.create');
        Route::post('documents', [App\Http\Controllers\Member\DocumentController::class, 'store'])->name('documents.store');
        Route::get('documents/{document}/download', [App\Http\Controllers\Member\DocumentController::class, 'download'])->name('documents.download');
        Route::delete('documents/{document}', [App\Http\Controllers\Member\DocumentController::class, 'destroy'])->name('documents.destroy');
    });
});

// require __DIR__.'/auth.php'; // Commented out - using custom member auth system

// Webhook Stripe (route publique)
Route::post('webhook/stripe', [App\Http\Controllers\Member\PaymentProcessingController::class, 'webhook'])->name('webhook.stripe');


