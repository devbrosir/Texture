<?php

declare(strict_types=1);

namespace App\Facades;

use App\Enums\ActivityType;
use App\Services\ActivityService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void add(array $items)
 * @method static void log(ActivityType $type, Model | null $related, int | null $userId = null, array $metadata = [])
 */
class ActivityLogger extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ActivityService::class;
    }
}
