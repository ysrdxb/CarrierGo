<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // NOTE: Old registration routes REMOVED - using new RegisterTenant Livewire component instead
    // The new flow is: RegisterTenant (save to registrations) → Email Verification → CompanySetup (create user & tenant)
    // DO NOT add Route::get/post('register') here - it's defined in web.php as RegisterTenant Livewire

    // Self-registration email verification
    Route::get('register/verify/{token}', [RegistrationController::class, 'verifyEmail'])
                ->name('register.verify');

    Route::get('registration/pending', [RegistrationController::class, 'showPendingApproval'])
                ->name('registration.pending');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::get('resend-email-code', [AuthenticatedSessionController::class, 'resendEmailCode'])
                        ->name('otp.resend');

    Route::get('verify-email-code', [AuthenticatedSessionController::class, 'showEmailCodeForm'])
            ->name('otp.verify');

    Route::post('verify-email-code', [AuthenticatedSessionController::class, 'verifyEmailCode'])
                ->name('otp.verify');

    Route::post('login/verify', [AuthenticatedSessionController::class, 'verifyLogin'])
                ->name('verify-login');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])
                ->name('login');

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
                ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
                ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
                ->middleware(['signed', 'throttle:6,1'])
                ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
                ->middleware('throttle:6,1')
                ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');
});

Route::post('password', [PasswordController::class, 'update'])->name('password.update');

