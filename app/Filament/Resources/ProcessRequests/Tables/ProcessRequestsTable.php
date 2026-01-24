<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProcessRequests\Tables;

use App\Models\ProcessRequest;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class ProcessRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('نام کاربر')
                    ->state(fn (ProcessRequest $record) => $record->user->name)
                    ->searchable(),
                ImageColumn::make('image')->label('تصویر')
                    ->defaultImageUrl(fn (ProcessRequest $record): string => $record->getFirstMediaUrl(ProcessRequest::IMAGES)),
                TextColumn::make('mobile')->label('موبایل')
                    ->state(fn (ProcessRequest $record) => $record->user->mobile)
                    ->searchable(),
                TextColumn::make('created_at')->label('زمان ارسال')
                    ->state(fn (ProcessRequest $record) => $record->created_at?->toJalali()->format('Y/m/d - H:i:s'))
                    ->sortable(),
                TextColumn::make('status')->label('وضعیت')
                    ->state(fn (ProcessRequest $record) => $record->status->trans())
                    ->sortable()
                    ->searchable(),
                TextColumn::make('processed_at')->label('زمان انجام')
                    ->state(fn (ProcessRequest $record) => $record->processed_at?->toJalali()->format('Y/m/d - H:i:s'))
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])->emptyStateHeading('هنوز درخواستی وجود ندارد');
    }
}
