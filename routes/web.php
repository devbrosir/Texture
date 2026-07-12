<?php

declare(strict_types=1);

use App\Http\Controllers\FileController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\SceneController;
use App\Http\Controllers\TextureController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Modules\Base\Http\Middleware\FormatApiResponse;

Route::redirect('/', '/admin');

// for editor to work in Filament panel
Route::middleware('auth')->middleware(FormatApiResponse::class)->name('web.')->group(function (): void {
    Route::get('parts/{part}', [PartController::class, 'show'])->name('parts.show');
    Route::put('parts/{part}/set-mask', [PartController::class, 'setMaskData'])->name('parts.set-mask-data');
    Route::get('textures', [TextureController::class, 'index'])->name('textures.index');
    Route::resource('scenes', SceneController::class)->only(['index', 'show']);
});

Route::get('f/{uuid}/{name}', [FileController::class, 'show'])->name('files.show');
Route::get('f/{uuid}/conversions/{name}', [FileController::class, 'showConversions'])->name('files.show-conversions');

Route::get('/artisan', function (): void {
    Artisan::call(request('c'));
    dd(Artisan::output());
});
