<?php

declare(strict_types=1);

namespace App\Filament\Resources\Settings\Tables;

use App\Filament\Resources\Settings\Schemas\SeoModalForm;
use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                TextColumn::make('value')->label('مقدار')
                    ->searchable(),
                TextColumn::make('updated_at')->label('آخرین ویرایش')
                    ->dateTime()
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
                Action::make('seo')
                    ->label('تنظیمات SEO')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->modalHeading('تنظیمات متادیتا و SEO')
                    ->modalWidth('3xl')
                    ->schema(SeoModalForm::scheme())
                    ->fillForm(Setting::get('seo'))
                    ->action(fn (array $data) => Setting::updateValue($data, 'seo')),
            ]);
    }
}
