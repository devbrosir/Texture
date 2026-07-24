<?php

declare(strict_types=1);

namespace App\Filament\Resources\Textures\Schemas;

use App\Models\Texture;
use App\Models\TextureCategory;
use App\Models\TextureType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
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
                Select::make('type_id')
                    ->label('نوع تکسچر')
                    ->options(TextureType::query()->pluck('title', 'id'))
                    ->live()
                    ->required()
                    ->afterStateUpdated(fn (Set $set): mixed => $set('category_id', null))
                    ->createOptionForm([
                        TextInput::make('title')->required(),
                    ])
                    ->createOptionUsing(fn (array $data) => TextureType::query()->create($data)->id),

                Select::make('category_id')
                    ->label('دسته تکسچر')
                    ->options(fn (Get $get) => TextureCategory::query()
                        ->when(
                            $get('type_id'),
                            fn ($query, $typeId) => $query->where('type_id', $typeId),
                        )->pluck('title', 'id'))
                    ->preload()
                    ->required()
                    ->createOptionForm(fn (Get $get): array => [
                        TextInput::make('title')->required(),

                        Select::make('type_id')
                            ->label('نوع تکسچر')
                            ->options(TextureType::query()->pluck('title', 'id'))
                            ->default($get('type_id'))
                            ->required(),
                    ])
                    ->createOptionUsing(fn (array $data) => TextureCategory::query()->create($data)->id),

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
