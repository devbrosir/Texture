<?php

declare(strict_types=1);

namespace App\Filament\Resources\Activities\Tables;

use App\Models\Activity;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Modules\User\Models\User;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class ActivitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(Activity::query()->latest())
            ->columns([
                TextColumn::make('user.name')->label('کاربر')
                    ->searchable()
                    ->badge()
                    ->url(function (Activity $activity): ?string {
                        if ($activity->user_id) {
                            return route('filament.admin.resources.users.view', ['record' => $activity->user_id]);
                        }

                        return null;
                    })
                    ->openUrlInNewTab(),
                TextColumn::make('typeTitle')->label('عملیات'),
                TextColumn::make('related')->label('مربوط به')
                    ->state(fn (Activity $activity) => $activity->related?->title ?? $activity->related?->name ?? $activity->related?->getLabel() ?? '')
                    ->url(function (Activity $activity): ?string {
                        try {
                            if ($activity->related_id) {
                                $routeKey = Str::plural(Str::kebab(class_basename($activity->related_type)));
                                if (Route::has("filament.admin.resources.$routeKey.view")) {
                                    $routeName = "filament.admin.resources.$routeKey.view";
                                } elseif (Route::has("filament.admin.resources.$routeKey.edit")) {
                                    $routeName = "filament.admin.resources.$routeKey.edit";
                                } else {
                                    return null;
                                }

                                return route($routeName, ['record' => $activity->related_id]);
                            }
                        } catch (RouteNotFoundException) {
                            return null;
                        }

                        return null;
                    })
                    ->badge()
                    ->openUrlInNewTab(),
                TextColumn::make('created_at')->label('زمان')
                    ->state(fn (Activity $activity) => $activity->created_at?->toJalali()->format('Y/m/d - H:i:s'))
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('user_id')->label('کاربر')->searchable()->options(
                    User::query()->pluck('name', 'id')
                ),
            ])
            ->recordActions([
                ViewAction::make()->label('جزئیات'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
