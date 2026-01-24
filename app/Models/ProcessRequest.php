<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RequestStatus;
use Database\Factories\ProcessRequestFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\Base\Support\BaseModel;
use Modules\User\Models\User;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property-read RequestStatus $status
 * @property-read int $user_id
 * @property-read Carbon|null $processed_at
 * @property-read string|null $description
 * @property-read Carbon $created_at
 * @property-read User $user
 * @property-read Collection<Media> $images
 */
final class ProcessRequest extends BaseModel implements HasMedia
{
    /** @use HasFactory<ProcessRequestFactory> */
    use HasFactory;

    use InteractsWithMedia;

    // collection name
    public const string IMAGES = 'images';

    protected $casts = [
        'status' => RequestStatus::class,
        'processed_at' => 'datetime',
    ];

    public static function booted(): void
    {
        self::updating(function (ProcessRequest $model): void {
            if ($model->isDirty('status') && $model->status === RequestStatus::COMPLETED) {
                $model->processed_at = now();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ownedBy(User|int|null $user = null): bool
    {
        return $this->user_id === ($user->id ?? $user ?? auth()->id());
    }

    public function images(): Attribute
    {
        return Attribute::get(fn (): MediaCollection => $this->getMedia(self::IMAGES));
    }
}
