<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ActivityType;
use App\Models\Activity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ActivityService
{
    public function add(array $items): void
    {
        if (isset($items['type'])) {
            $items = [$items];
        }
        $now = now();
        foreach ($items as &$item) {
            $item['user_id'] ??= auth()->id();
            $item['created_at'] ??= $now;
            $item['related_type'] = isset($item['related_type']) ? 'App\\Models\\'.Str::pascal($item['related_type']) : null;
            $item['related_id'] ??= null;
            $item['metadata'] ??= null;
        }
        Activity::query()->fillAndInsert($items);
    }

    public function log(ActivityType $type, ?Model $related, ?int $userId = null, array $metadata = []): void
    {
        $this->add([
            'user_id' => $userId,
            'type' => $type->value,
            'related_type' => $related instanceof Model ? $related::class : null,
            'related_id' => $related?->id,
            'metadata' => $metadata,
        ]);
    }
}
