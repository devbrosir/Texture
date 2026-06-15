<?php

declare(strict_types=1);

namespace App\Filament\Resources\Scenes\Schemas;

use App\Models\Scene;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
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
                    ->maxSize(4096)
                    ->columnSpanFull(),
                TextEntry::make('description')->hiddenLabel()
                    ->hiddenOn('edit')
                    ->color('warning')
                    ->extraAttributes(['style' => 'font-weight: bold;'])
                    ->default('برای ایجاد بخش ها، ابتدا محیط را ذخیره کنید.'),
            ]);
    }
}
