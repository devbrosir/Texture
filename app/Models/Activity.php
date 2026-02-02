<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ActivityType;
use Database\Factories\ActivityFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Modules\Base\Support\BaseModel;
use Modules\User\Models\User;

/**
 * @property-read int $id
 * @property-read int | null $user_id
 * @property-read ActivityType $type
 * @property-read int | null $related_id
 * @property-read string | null $related_type
 * @property-read array $metadata
 * @property-read string $typeTitle
 * @property-read User | null $user
 * @property-read Model | null $related
 * @property-read Carbon $created_at
 */
class Activity extends BaseModel
{
    /** @use HasFactory<ActivityFactory> */
    use HasFactory;

    public const null UPDATED_AT = null;

    protected $casts = [
        'type' => ActivityType::class,
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    protected function typeTitle(): Attribute
    {
        return Attribute::make(get: fn (): string|array|null => $this->type->trans());
    }
}
