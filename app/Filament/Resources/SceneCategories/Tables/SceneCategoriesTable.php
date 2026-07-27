<?php

declare(strict_types=1);

namespace App\Filament\Resources\SceneCategories\Tables;

use App\Models\SceneCategory;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Base\Filament\Actions\SafeDeleteAction;

class SceneCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                SpatieMediaLibraryImageColumn::make('image')->label('تصویر')
                    ->collection(SceneCategory::IMAGE)
                    ->imageHeight(90)
                    ->imageWidth(160),
            ])
            ->recordActions([
                EditAction::make(),
                SafeDeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
