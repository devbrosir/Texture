<?php

declare(strict_types=1);

namespace App\Filament\Resources\SceneCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SceneCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title'),
            ]);
    }
}
