<?php

declare(strict_types=1);

namespace App\Filament\Resources\Textures\Pages;

use App\Filament\Resources\Textures\TextureResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTexture extends EditRecord
{
    protected static string $resource = TextureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
