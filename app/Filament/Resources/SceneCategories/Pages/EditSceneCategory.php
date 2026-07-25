<?php

declare(strict_types=1);

namespace App\Filament\Resources\SceneCategories\Pages;

use App\Filament\Resources\SceneCategories\SceneCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSceneCategory extends EditRecord
{
    protected static string $resource = SceneCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
