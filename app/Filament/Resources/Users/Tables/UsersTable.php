<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Tables;

use Carbon\CarbonImmutable;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Modules\User\Enums\Role;
use Modules\User\Enums\UserStatus;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('نام'),
                TextColumn::make('mobile')->label('موبایل')
                    ->searchable(),
                TextColumn::make('role')->label('نقش')
                    ->formatStateUsing(fn (Role $state): string => $state->trans()),
                TextColumn::make('created_at')->label('زمان ثبت نام')
                    ->formatStateUsing(fn (?CarbonImmutable $state) => $state->toJalali()->format('Y-m-d - H:i:s')),
                TextColumn::make('wp_id')->label('آی دی وردپرس'),
            ])
            ->filters([
                //
            ]);
    }
}
