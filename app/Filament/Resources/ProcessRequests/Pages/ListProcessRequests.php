<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProcessRequests\Pages;

use App\Filament\Resources\ProcessRequests\ProcessRequestResource;
use Filament\Resources\Pages\ListRecords;

final class ListProcessRequests extends ListRecords
{
    protected static string $resource = ProcessRequestResource::class;

    public function getHeaderActions(): array
    {
        return [];
    }
}
