<?php

declare(strict_types=1);

namespace App\Filament\Resources\TextureCategories\Pages;

use App\Filament\Resources\TextureCategories\TextureCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTextureCategory extends CreateRecord
{
    protected static string $resource = TextureCategoryResource::class;
}
