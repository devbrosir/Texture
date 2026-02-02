<?php

declare(strict_types=1);

namespace App\Filament\Resources\Activities\Schemas;

use App\Models\Activity;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

final class ActivityInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')->label('کاربر')
                    ->placeholder('-'),
                TextEntry::make('typeTitle')->label('عملیات')
                    ->placeholder('-'),
                TextEntry::make('related')->label('مربوط به')
                    ->state(fn (Activity $activity) => $activity->related?->title ?? $activity->related?->name ?? '')
                    ->placeholder('-'),
                TextEntry::make('created_at')->label('زمان')
                    ->state(fn (Activity $activity) => $activity->created_at?->toJalali()->format('Y/m/d - H:i:s'))
                    ->placeholder('-'),
                KeyValueEntry::make('metadata')->label('جزئیات')
                    ->state(fn (Activity $activity) => $activity->metadata)
                    ->columnSpanFull(),
            ]);
    }
}
