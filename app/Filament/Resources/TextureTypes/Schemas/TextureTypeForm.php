<?php

declare(strict_types=1);

namespace App\Filament\Resources\TextureTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TextureTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title'),
            ]);
    }
}
