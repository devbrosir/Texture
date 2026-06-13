<?php

declare(strict_types=1);

namespace App\Filament\Resources\Textures\Schemas;

use App\Models\Texture;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TextureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')->label('عنوان')
                    ->required(),
                TextInput::make('width')->label('عرض')
                    ->required()
                    ->numeric(),
                TextInput::make('height')->label('ارتفاع')
                    ->required()
                    ->numeric(),
                SpatieMediaLibraryFileUpload::make('texture_image')
                    ->label('تصویر تکسچر')
                    ->collection(Texture::TEXTURE)
                    ->panelLayout('integrated')
                    ->removeUploadedFileButtonPosition('right bottom')
                    ->uploadButtonPosition('left bottom')
                    ->uploadProgressIndicatorPosition('left bottom')
                    ->acceptedFileTypes(['image/*'])
                    ->disk('public')
                    ->maxSize(2048),
            ]);
    }
}
