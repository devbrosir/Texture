<?php

declare(strict_types=1);

namespace App\Filament\Resources\TextureTypes\Pages;

use App\Filament\Resources\TextureTypes\TextureTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTextureType extends EditRecord
{
    protected static string $resource = TextureTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
