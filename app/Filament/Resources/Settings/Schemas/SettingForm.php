<?php

declare(strict_types=1);

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

final class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')->label('عنوان')
                    ->readOnlyOn('edit'),
                TextInput::make('key')->label('کلید')
                    ->required()->readOnlyOn('edit'),
                Group::make()
                    ->schema(function ($record): array {

                        if (! $record) {
                            return [
                                TextInput::make('value')
                                    ->label('Value'),
                            ];
                        }

                        $value = $record->value;

                        // Array / Object → Repeater
                        if (is_array($value)) {
                            return [
                                Repeater::make('value')
                                    ->schema([
                                        TextInput::make('key')
                                            ->label('Key')
                                            ->required(),

                                        TextInput::make('value')
                                            ->label('Value')
                                            ->required(),
                                    ])
                                    ->default(array_map(
                                        fn ($v, $k): array => ['key' => $k, 'value' => $v],
                                        $value,
                                        array_keys($value)
                                    )),
                            ];
                        }

                        // Boolean
                        if (is_bool($value)) {
                            return [
                                Toggle::make('value')
                                    ->label('Value'),
                            ];
                        }

                        // Number
                        if (is_numeric($value)) {
                            return [
                                TextInput::make('value')
                                    ->numeric()
                                    ->label('Value'),
                            ];
                        }

                        // String (fallback)
                        return [
                            TextInput::make('value')
                                ->label('Value'),
                        ];
                    }),
            ]);
    }
}
