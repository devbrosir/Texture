<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Modules\User\Enums\Role;
use Modules\User\Enums\UserStatus;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->label('نام')
                    ->required(),
                TextInput::make('mobile')->label('موبایل')
                    ->unique('users', 'mobile')
                    ->required()
                    ->tel()
                    ->prefixIcon('heroicon-o-device-phone-mobile')
                    ->rules([
                        'required',
                        'regex:/^09[0-9]{9}$/',
                    ]),
                Select::make('role')->label('نقش')
                    ->options(Role::toOptions())
                    ->required(),
                Select::make('status')->label('وضعیت')
                    ->options(UserStatus::toOptions())
                    ->required(),
                TextInput::make('password')->label('پسورد')
                    ->hiddenOn('edit')
                    ->required(),
            ]);
    }
}
