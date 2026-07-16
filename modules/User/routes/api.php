<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Middleware\SanctumMiddleware;
use Modules\User\Http\Controllers\Api\v1\Auth\LoginController;
use Modules\User\Http\Controllers\Api\v1\ProfileController;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function (): void {
        // WordPress login
        Route::post('wp-login', [LoginController::class, 'wordpressLogin'])->name('wp-login');

        Route::post('send-otp', [LoginController::class, 'sendOtp'])->name('send-otp');
        Route::post('verify', [LoginController::class, 'verify'])->name('verify');
    });

    Route::middleware(SanctumMiddleware::class)->group(function () {
        Route::prefix('profile')->group(function (): void {
            Route::post('logout', [ProfileController::class, 'logout'])->name('logout');
            Route::get('user', [ProfileController::class, 'user'])->name('user');
            Route::get('', [ProfileController::class, 'show'])->name('show');
            Route::put('', [ProfileController::class, 'update'])->name('update');
        });
    });
});
