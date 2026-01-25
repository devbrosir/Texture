<?php

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use Filament\Resources\Pages\ListRecords;

final class ListSettings extends ListRecords
{
    public static string $resource = SettingResource::class;

    public function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
