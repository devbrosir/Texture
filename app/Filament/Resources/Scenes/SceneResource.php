<?php

declare(strict_types=1);

namespace App\Filament\Resources\Scenes;

use App\Filament\Resources\Scenes\Pages\CreateScene;
use App\Filament\Resources\Scenes\Pages\EditScene;
use App\Filament\Resources\Scenes\Pages\ListScenes;
use App\Filament\Resources\Scenes\RelationManagers\PartsRelationManager;
use App\Filament\Resources\Scenes\Schemas\SceneForm;
use App\Filament\Resources\Scenes\Tables\ScenesTable;
use App\Models\Scene;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

final class SceneResource extends Resource
{
    protected static ?string $model = Scene::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getModelLabel(): string
    {
        return 'محیط';
    }

    public static function getPluralLabel(): string
    {
        return 'محیط ها';
    }

    public static function form(Schema $schema): Schema
    {
        return SceneForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ScenesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            PartsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListScenes::route('/'),
            'create' => CreateScene::route('/create'),
            'edit' => EditScene::route('/{record}/edit'),
        ];
    }
}
