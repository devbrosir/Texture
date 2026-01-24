<?php

declare(strict_types=1);

namespace App\Filament\Resources\ProcessRequests\Schemas;

use App\Enums\RequestStatus;
use App\Models\ProcessRequest;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class ProcessRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageEntry::make('image')->label('تصویر')
                    ->defaultImageUrl(fn (ProcessRequest $record): string => $record->getFirstMediaUrl(ProcessRequest::IMAGES))
                    ->imageSize('30%')
                    ->alignCenter()
                    ->columnSpanFull(),
                TextEntry::make('status')->label('وضعیت')
                    ->state(fn (ProcessRequest $record) => $record->status->trans())
                    ->placeholder('-'),
                TextEntry::make('name')->label('نام کاربر')
                    ->state(fn (ProcessRequest $record) => $record->user->name)
                    ->placeholder('-'),
                TextEntry::make('mobile')->label('موبایل')
                    ->state(fn (ProcessRequest $record) => $record->user->mobile)
                    ->placeholder('-'),
                TextEntry::make('created_at')->label('زمان ارسال')
                    ->state(fn (ProcessRequest $record) => $record->created_at?->toJalali()->format('Y/m/d - H:i:s'))
                    ->placeholder('-'),
                TextEntry::make('processed_at')->label('زمان انجام')
                    ->state(fn (ProcessRequest $record) => $record->processed_at?->toJalali()->format('Y/m/d - H:i:s'))
                    ->placeholder('-'),
                TextEntry::make('description')->label('توضیحات کاربر')
                    ->placeholder('-')
                    ->columnSpanFull(),

                Select::make('status')->label('وضعیت')
                    ->options(RequestStatus::toOptions()),
            ]);
    }
}
