<?php

declare(strict_types=1);

namespace App\Filament\Resources\SceneCategories\Pages;

use App\Filament\Resources\SceneCategories\SceneCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSceneCategories extends ListRecords
{
    protected static string $resource = SceneCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
