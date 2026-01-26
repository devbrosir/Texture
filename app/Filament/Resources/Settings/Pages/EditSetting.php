<?php

declare(strict_types=1);

namespace App\Filament\Resources\Settings\Pages;

use App\Filament\Resources\Settings\SettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditSetting extends EditRecord
{
    public static string $resource = SettingResource::class;

    public function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    public function mutateFormDataBeforeSave(array $data): array
    {
        // has repeater style
        if (isset($data['value'][0]['key']) && is_array($data['value'])) {
            $data['value'] = collect($data['value'])
                ->mapWithKeys(fn ($item): array => [$item['key'] => $item['value']])
                ->toArray();
        }

        return $data;
    }
}
