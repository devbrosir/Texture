<?php

declare(strict_types=1);

namespace App\Filament\Resources\Banners\Tables;

use App\Models\Banner;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

final class BannersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')->label('تصویر')
                    ->collection(Banner::IMAGE)
                    ->imageSize(60)
                    ->square(),
                TextColumn::make('link')->label('لینک'),
                TextColumn::make('delay')->label('تاخیر نمایش (ثانیه)'),
                ToggleColumn::make('active')->label('فعال'),
                TextColumn::make('show_count')->label('دفعات نمایش در شبانه روز'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
