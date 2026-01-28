<?php

declare(strict_types=1);

namespace App\Filament\Resources\Scenes\Pages;

use App\Filament\Resources\Scenes\SceneResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListScenes extends ListRecords
{
    protected static string $resource = SceneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
