<?php

declare(strict_types=1);

namespace App\Filament\Resources\SceneCategories\Pages;

use App\Filament\Resources\SceneCategories\SceneCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSceneCategory extends CreateRecord
{
    protected static string $resource = SceneCategoryResource::class;
}
