<?php

declare(strict_types=1);

namespace App\Filament\Resources\Settings\Tables;

use App\Models\Setting;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\Base\Filament\Extend\JalaliTextColumn;

final class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('عنوان')
                    ->searchable(),
                TextColumn::make('key')->label('کلید')
                    ->searchable(),
                TextColumn::make('the_value')->label('مقدار')
                    ->searchable()
                    ->formatStateUsing(fn (Setting $setting) => $setting->show ? $setting->value : '...'),
                JalaliTextColumn::make('updated_at')->label('آخرین ویرایش')
                    ->jalaliDateTime()
                    ->sortable(),
            ])
            ->query(fn ($query) => Setting::query()->where('show', true))
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
