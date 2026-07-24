<?php

declare(strict_types=1);

namespace App\Filament\Resources\TextureCategories;

use App\Filament\Resources\TextureCategories\Pages\CreateTextureCategory;
use App\Filament\Resources\TextureCategories\Pages\EditTextureCategory;
use App\Filament\Resources\TextureCategories\Pages\ListTextureCategories;
use App\Filament\Resources\TextureCategories\Schemas\TextureCategoryForm;
use App\Filament\Resources\TextureCategories\Tables\TextureCategoriesTable;
use App\Models\TextureCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TextureCategoryResource extends Resource
{
    protected static ?string $model = TextureCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return TextureCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TextureCategoriesTable::configure($table);
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
            'index' => ListTextureCategories::route('/'),
            'create' => CreateTextureCategory::route('/create'),
            'edit' => EditTextureCategory::route('/{record}/edit'),
        ];
    }
}
