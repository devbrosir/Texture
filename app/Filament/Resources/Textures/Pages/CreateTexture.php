<?php

declare(strict_types=1);

namespace App\Filament\Resources\Textures\Pages;

use App\Filament\Resources\Textures\TextureResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTexture extends CreateRecord
{
    protected static string $resource = TextureResource::class;
}
