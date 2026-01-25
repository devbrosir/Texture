<?php

declare(strict_types=1);

use App\Http\Controllers\BannerController;
use App\Http\Controllers\ProcessRequestController;
use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Middleware\SanctumMiddleware;

Route::prefix('v1')->group(function (): void {
    Route::middleware(SanctumMiddleware::class)->group(function (): void {
        Route::get('requests', [ProcessRequestController::class, 'index'])
            ->name('process-requests.index');
        Route::post('requests', [ProcessRequestController::class, 'store'])
            ->name('process-requests.store');
        Route::post('requests/{process_request}/cancel', [ProcessRequestController::class, 'cancel'])
            ->name('process-requests.cancel');
    });

    // Banners
    Route::get('banners', [BannerController::class, 'index'])->name('banners.index');
});
