<?php

declare(strict_types=1);

namespace App\Filament\Resources\Textures;

use App\Filament\Resources\Textures\Pages\CreateTexture;
use App\Filament\Resources\Textures\Pages\EditTexture;
use App\Filament\Resources\Textures\Pages\ListTextures;
use App\Filament\Resources\Textures\Schemas\TextureForm;
use App\Filament\Resources\Textures\Tables\TexturesTable;
use App\Models\Texture;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TextureResource extends Resource
{
    protected static ?string $model = Texture::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $modelLabel = 'تکسچر';

    protected static ?string $pluralModelLabel = 'تکسچرها';

    public static function form(Schema $schema): Schema
    {
        return TextureForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TexturesTable::configure($table);
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
            'index' => ListTextures::route('/'),
            'create' => CreateTexture::route('/create'),
            'edit' => EditTexture::route('/{record}/edit'),
        ];
    }
}
