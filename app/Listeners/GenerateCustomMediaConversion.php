<?php

declare(strict_types=1);

namespace App\Listeners;

namespace App\Listeners;

use App\MediaLibrary\HasCustomConversions;
use App\Services\ConversionGenerator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent;

class GenerateCustomMediaConversion implements ShouldQueue
{
    public function __construct(protected ConversionGenerator $conversionGenerator) {}

    public function handle(MediaHasBeenAddedEvent $event): void
    {
        $model = $event->media->model;

        if (! in_array(HasCustomConversions::class, class_uses_recursive($model), true)) {
            return;
        }

        /** @var HasCustomConversions $model */
        foreach ($model->getCustomConversions() as $conversion) {

            $this->conversionGenerator
                ->conversion($conversion->name)
                ->width($conversion->width)
                ->height($conversion->height)
                ->generate($event->media);
        }
    }
}
