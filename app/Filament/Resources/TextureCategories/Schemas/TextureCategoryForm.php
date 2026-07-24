<?php

declare(strict_types=1);

namespace App\Filament\Resources\TextureCategories\Schemas;

use App\Models\TextureType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TextureCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title'),
                Select::make('type_id')
                    ->options(TextureType::all()->pluck('title', 'id')->toArray()),
            ]);
    }
}
