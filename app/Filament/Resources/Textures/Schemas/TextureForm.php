<?php

declare(strict_types=1);

namespace App\Filament\Resources\Textures\Schemas;

use App\Enums\TextureType;
use App\Models\Texture;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TagsInput;
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
                Select::make('type')->label('نوع تکسچر')
                    ->options(TextureType::toOptions())
                    ->required(),
                TextInput::make('color')->label('رنگ')
                    ->required(),
                TagsInput::make('tags')->label('تگ ها')
                    ->hint('مثلا جنس متریال مثل کاغذ، رنگ، پارکت و هر مورد دیگر')
                    ->distinctList()
                    ->required(),
                TextInput::make('product_url')->label('آدرس محصول مرتبط'),
                SpatieMediaLibraryFileUpload::make('texture_image')
                    ->label('تصویر تکسچر')
                    ->collection(Texture::TEXTURE)
                    ->panelLayout('integrated')
                    ->removeUploadedFileButtonPosition('right bottom')
                    ->uploadButtonPosition('left bottom')
                    ->uploadProgressIndicatorPosition('left bottom')
                    ->acceptedFileTypes(['image/*'])
                    ->disk('public')
                    ->maxSize(4096),
            ]);
    }
}
