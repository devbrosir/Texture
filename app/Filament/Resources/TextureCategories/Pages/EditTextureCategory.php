<?php

declare(strict_types=1);

namespace App\Filament\Resources\TextureCategories\Pages;

use App\Filament\Resources\TextureCategories\TextureCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTextureCategory extends EditRecord
{
    protected static string $resource = TextureCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
