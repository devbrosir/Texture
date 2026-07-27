<?php

declare(strict_types=1);

namespace App\Filament\Resources\SceneCategories\Schemas;

use App\Models\SceneCategory;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SceneCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title'),
                SpatieMediaLibraryFileUpload::make('image')->label('تصویر')
                    ->collection(SceneCategory::IMAGE)
                    ->disk('public')
                    ->maxSize(2048)
                    ->acceptedFileTypes(['image/*']),
            ]);
    }
}
