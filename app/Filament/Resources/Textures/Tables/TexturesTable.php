<?php

declare(strict_types=1);

namespace App\Filament\Resources\Textures\Tables;

use App\Models\Texture;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TexturesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('texture_image')->label('تصویر')
                    ->collection(Texture::TEXTURE)
                    ->imageSize(60)
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')->label('عنوان')
                    ->searchable(),
                TextColumn::make('width')->label('عرض')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('height')->label('ارتفاع')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
