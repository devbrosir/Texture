<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\SceneFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\Base\Support\BaseModel;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property-read string $title
 * @property-read array $tags
 * @property-read bool $active
 * @property-read array $mask
 * @property-read Collection<Part> $parts
 * @property-read string $image
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
final class Scene extends BaseModel implements HasMedia
{
    /** @use HasFactory<SceneFactory> */
    use HasFactory;

    use InteractsWithMedia;

    // collection name
    public const string IMAGE = 'image';

    protected $casts = [
        'active' => 'boolean',
        'tags' => 'array',
        'mask' => 'array',
    ];

    public function parts(): HasMany
    {
        return $this->hasMany(Part::class);
    }

    public function image(): Attribute
    {
        return new Attribute(get: fn () => $this->getFirstMedia(self::IMAGE)?->original_url);
    }
}
