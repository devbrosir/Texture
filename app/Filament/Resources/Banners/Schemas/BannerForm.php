<?php

declare(strict_types=1);

namespace App\Filament\Resources\Banners\Schemas;

use App\Models\Banner;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

final class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                SpatieMediaLibraryFileUpload::make('image')->collection(Banner::IMAGE)
                    ->disk('public')
                    ->maxSize(2048),
                TextInput::make('link')->label('لینک')->url()->required(),
                TextInput::make('delay')->label('تاخیر نمایش (ثانیه)')->numeric()->required(),
                Checkbox::make('active')->label('فعال'),
                TextInput::make('show_count')->label('دفعات نمایش در شبانه روز')->numeric()->required(),
            ]);
    }
}
