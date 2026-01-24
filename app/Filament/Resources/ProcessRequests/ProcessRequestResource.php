<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProcessRequests;

use App\Filament\Resources\ProcessRequests\Pages\EditProcessRequest;
use App\Filament\Resources\ProcessRequests\Pages\ListProcessRequests;
use App\Filament\Resources\ProcessRequests\Schemas\ProcessRequestForm;
use App\Filament\Resources\ProcessRequests\Schemas\ProcessRequestInfolist;
use App\Filament\Resources\ProcessRequests\Tables\ProcessRequestsTable;
use App\Models\ProcessRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

final class ProcessRequestResource extends Resource
{
    protected static ?string $model = ProcessRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'درخواست';

    protected static ?string $pluralModelLabel = 'درخواست ها';

    public static function form(Schema $schema): Schema
    {
        return ProcessRequestForm::configure($schema);
    }

    public static function getRecordTitle(?Model $record): string
    {
        return 'درخواست '.($record->name ?? $record->user->name ?? '');
    }

    public static function infolist(Schema $schema): Schema
    {
        return ProcessRequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProcessRequestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProcessRequests::route('/'),
            'edit' => EditProcessRequest::route('/{record}/edit'),
        ];
    }
}
