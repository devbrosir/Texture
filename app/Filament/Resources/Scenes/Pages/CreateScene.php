<?php

declare(strict_types=1);

namespace App\Filament\Resources\Scenes\Pages;

use App\Filament\Resources\Scenes\SceneResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateScene extends CreateRecord
{
    protected static string $resource = SceneResource::class;
}
