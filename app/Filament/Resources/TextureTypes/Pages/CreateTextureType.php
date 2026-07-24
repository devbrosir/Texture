<?php

declare(strict_types=1);

namespace App\Filament\Resources\TextureTypes\Pages;

use App\Filament\Resources\TextureTypes\TextureTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTextureType extends CreateRecord
{
    protected static string $resource = TextureTypeResource::class;
}
