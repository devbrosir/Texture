<?php

declare(strict_types=1);

namespace App\Filament\Resources\Scenes\Tables;

use App\Models\Scene;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

final class ScenesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(Scene::query()->withCount('parts'))
            ->columns([
                TextColumn::make('title')->label('نام')->searchable(),
                ToggleColumn::make('active')->label('وضعیت'),
                TextColumn::make('parts_count')->label('تعداد بخش ها')
                    ->state(fn (Scene $record) => $record->parts_count),
                TextColumn::make('category.title')->label('دسته بندی'),
                TextColumn::make('created_at')->label('زمان ایجاد')
                    ->state(fn (Scene $record) => $record->created_at?->toJalali()->format('Y/m/d - H:i:s'))
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('updated_at')->label('آخرین ویرایش')
                    ->state(fn (Scene $record) => $record->updated_at?->toJalali()->format('Y/m/d - H:i:s'))
                    ->sortable()
                    ->toggleable(),
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
