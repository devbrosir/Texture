<?php

declare(strict_types=1);

use App\Http\Controllers\PartController;
use App\Http\Controllers\TextureController;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

Route::get('/', fn (): View => view('welcome'));

Route::middleware('auth')->group(function (): void {
    Route::get('parts/{part}', [PartController::class, 'show'])->name('parts.show');
    Route::put('parts/{part}/set-mask', [PartController::class, 'setMaskData'])->name('parts.set-mask-data');
    Route::get('textures', [TextureController::class, 'index'])->name('textures.index');
});
