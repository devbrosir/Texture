<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProcessRequests\Pages;

use App\Filament\Resources\ProcessRequests\ProcessRequestResource;
use App\Filament\Resources\ProcessRequests\Schemas\ProcessRequestForm;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;

final class EditProcessRequest extends EditRecord
{
    protected static string $resource = ProcessRequestResource::class;

    public function form(Schema $schema): Schema
    {
        return ProcessRequestForm::configure($schema);
    }

    public function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
