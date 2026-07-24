<?php

declare(strict_types=1);

namespace App\Filament\Resources\TextureCategories\Pages;

use App\Filament\Resources\TextureCategories\TextureCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTextureCategories extends ListRecords
{
    protected static string $resource = TextureCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
