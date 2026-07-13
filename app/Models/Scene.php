<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasVersion;
use Database\Factories\SceneFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Modules\Base\Support\BaseModel;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property-read string $title
 * @property-read array $tags
 * @property-read bool $active
 * @property-read int $version
 * @property-read Collection<Part> $parts
 * @property-read string $image
 * @property-read string $thumbnail
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
final class Scene extends BaseModel implements HasMedia
{
    /** @use HasFactory<SceneFactory> */
    use HasFactory;

    use HasVersion;
    use InteractsWithMedia;

    // collection name
    public const string IMAGE = 'image';

    protected $casts = [
        'active' => 'boolean',
        'tags' => 'array',
    ];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')->width(80)->height(80)->keepOriginalImageFormat();
    }

    public function parts(): HasMany
    {
        return $this->hasMany(Part::class);
    }

    protected function image(): Attribute
    {
        return new Attribute(get: fn (): string => $this->getFirstMediaUrl(self::IMAGE));
    }

    protected function thumbnail(): Attribute
    {
        return new Attribute(get: fn (): string => $this->getFirstMediaUrl(self::IMAGE, 'thumbnail'));
    }

    protected function getVersionableFields(): array
    {
        return [];
    }

    protected function getVersionableMediaCollection(): string
    {
        return self::IMAGE;
    }
}
