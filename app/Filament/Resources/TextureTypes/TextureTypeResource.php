<?php

declare(strict_types=1);

namespace App\Filament\Resources\TextureTypes;

use App\Filament\Resources\TextureTypes\Pages\CreateTextureType;
use App\Filament\Resources\TextureTypes\Pages\EditTextureType;
use App\Filament\Resources\TextureTypes\Pages\ListTextureTypes;
use App\Filament\Resources\TextureTypes\Schemas\TextureTypeForm;
use App\Filament\Resources\TextureTypes\Tables\TextureTypesTable;
use App\Models\TextureType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TextureTypeResource extends Resource
{
    protected static ?string $model = TextureType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|null|UnitEnum $navigationGroup = 'دسته‌ها';

    protected static ?int $navigationSort = 2;

    public static function getPluralLabel(): ?string
    {
        return __('Texture Types');
    }

    public static function getModelLabel(): string
    {
        return __('Texture Type');
    }

    public static function form(Schema $schema): Schema
    {
        return TextureTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TextureTypesTable::configure($table);
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
            'index' => ListTextureTypes::route('/'),
            'create' => CreateTextureType::route('/create'),
            'edit' => EditTextureType::route('/{record}/edit'),
        ];
    }
}
