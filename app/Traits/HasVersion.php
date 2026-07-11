<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Part;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasVersion
{
    abstract protected function getVersionableFields(): array;

    abstract protected function getVersionableMediaCollection(): ?string;

    protected static function bootHasVersion(): void
    {
        self::saving(function (Model $model): void {
            $shouldIncrement = false;

            $watchedFields = $model->getVersionableFields();

            if ($watchedFields && $model->isDirty($watchedFields)) {
                $shouldIncrement = true;
            }

            if (! $shouldIncrement && $model->exists && in_array(HasMedia::class, class_implements($model))) {

                $collectionName = $model->getVersionableMediaCollection();

                if ($collectionName) {
                    $requestTime = now()->createFromTimestamp(LARAVEL_START, config('app.timezone'));

                    $hasNewMedia = Media::query()
                        ->where('model_type', $model::class)
                        ->where('model_id', $model->id)
                        ->where('collection_name', $collectionName)
                        ->where('created_at', '>=', $requestTime)
                        ->exists();

                    if ($hasNewMedia) {
                        $shouldIncrement = true;
                    }
                }
            }

            if ($shouldIncrement) {
                $model->version += 1;
                if ($model instanceof Part) {
                    $model->scene()->increment('version');
                }
            }
        });

        self::created(function (Model $model): void {
            if ($model instanceof Part) {
                $model->scene()->increment('version');
            }
        });

        self::deleted(function (Model $model): void {
            if ($model instanceof Part) {
                $model->scene()->increment('version');
            }
        });
    }
}
