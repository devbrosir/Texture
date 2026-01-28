<?php

declare(strict_types=1);

namespace App\Filament\Resources\Scenes\Schemas;

use App\Models\Scene;
use App\Models\Texture;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

final class SceneForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')->label('نام محیط')->required(),
                TagsInput::make('tags')->label('تگ ها'),
                SpatieMediaLibraryFileUpload::make('image')->label('تصویر')
                    ->collection(Scene::IMAGE)
                    ->disk('public')
                    ->maxSize(2048)
                    ->columnSpanFull(),
                Repeater::make('parts')->label('بخش ها')
                    ->relationship('parts')
                    ->schema([
                        TextInput::make('title')->label('نام بخش')->required()
                            ->live(),
                        Toggle::make('active')->label('فعال'),

                        Repeater::make('textures')->label('تکسچرها')
                            ->relationship('textures')
                            ->schema([
                                TextInput::make('title')->hiddenLabel()->placeholder('نام تکسچر'),
                                SpatieMediaLibraryFileUpload::make('texture')->hiddenLabel()
                                    ->collection(Texture::TEXTURE)
                                    ->panelAspectRatio('1:1')
                                    ->panelLayout('integrated')
                                    ->removeUploadedFileButtonPosition('right bottom')
                                    ->uploadButtonPosition('left bottom')
                                    ->uploadProgressIndicatorPosition('left bottom')
                                    ->acceptedFileTypes(['image/*'])
                                    ->disk('public')
                                    ->maxSize(2048),
                            ])
                            ->compact()
                            ->columnSpanFull()
                            ->grid(4),
                    ])->columns(2)
                    ->orderColumn('created_at')
                    ->columnSpanFull()
                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null),
                Textarea::make('mask')->label('mask')->columnSpanFull(),
            ]);
    }
}
