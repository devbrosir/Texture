<?php

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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['value']) && is_array($data['value'])) {
            // has repeater style
            if (isset($data['value'][0]['key'])) {
                $data['value'] = collect($data['value'])
                    ->mapWithKeys(fn ($item) => [$item['key'] => $item['value']])
                    ->toArray();
            }
        }

        return $data;
    }
}
