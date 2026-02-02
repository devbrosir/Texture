<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Schemas;

use Carbon\CarbonImmutable;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Modules\User\Enums\Role;
use Modules\User\Enums\UserStatus;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')->label('نام'),
                TextEntry::make('mobile')->label('موبایل'),
                TextEntry::make('role')->label('نقش')
                    ->formatStateUsing(fn (Role $state): string => $state->trans()),
                TextEntry::make('status')->label('وضعیت')
                    ->formatStateUsing(fn (UserStatus $state): string => $state->trans()),
                TextEntry::make('created_at')->label('زمان ثبت نام')
                    ->formatStateUsing(fn (?CarbonImmutable $state) => $state->toJalali()->format('Y-m-d - H:i:s')),
                TextEntry::make('wp_id')->label('آی دی وردپرس'),
            ]);
    }
}
