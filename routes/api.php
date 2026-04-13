<?php

declare(strict_types=1);

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\ProcessRequestController;
use App\Http\Controllers\SceneController;
use App\Http\Controllers\SettingController;
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

        Route::resource('scenes', SceneController::class)->only(['index', 'show']);
        Route::put('scenes/{scene}/set-mask', [SceneController::class, 'setMaskData'])->name('scenes.set-mask-data');

        Route::post('activities', [ActivityController::class, 'batchStore'])->name('activity.batch-store');
    });

    // Banners
    Route::get('banners', [BannerController::class, 'index'])->name('banners.index');

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
});
