<?php

declare(strict_types=1);

namespace App\Filament\Resources\SceneCategories;

use App\Filament\Resources\SceneCategories\Pages\CreateSceneCategory;
use App\Filament\Resources\SceneCategories\Pages\EditSceneCategory;
use App\Filament\Resources\SceneCategories\Pages\ListSceneCategories;
use App\Filament\Resources\SceneCategories\Schemas\SceneCategoryForm;
use App\Filament\Resources\SceneCategories\Tables\SceneCategoriesTable;
use App\Models\SceneCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SceneCategoryResource extends Resource
{
    protected static ?string $model = SceneCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|UnitEnum|null $navigationGroup = 'دسته‌ها';

    protected static ?int $navigationSort = 1;

    public static function getPluralLabel(): ?string
    {
        return __('Scene Categories');
    }

    public static function getModelLabel(): string
    {
        return __('Scene Category');
    }

    public static function form(Schema $schema): Schema
    {
        return SceneCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SceneCategoriesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSceneCategories::route('/'),
            'create' => CreateSceneCategory::route('/create'),
            'edit' => EditSceneCategory::route('/{record}/edit'),
        ];
    }
}
