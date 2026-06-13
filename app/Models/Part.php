<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PartFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Modules\Base\Support\BaseModel;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property-read string $title
 * @property-read bool $active
 * @property-read int $scene_id
 * @property-read int $default_texture_id
 * @property-read array $mask_config
 * @property-read string $mask
 * @property-read string $thumbnail
 * @property-read Scene $scene
 * @property-read Collection<Media> $textures
 */
final class Part extends BaseModel implements HasMedia
{
    /** @use HasFactory<PartFactory> */
    use HasFactory;

    use InteractsWithMedia;

    public const string MASK = 'MASK';

    protected $casts = [
        'active' => 'boolean',
        'mask_config' => 'array',
    ];

    public function scene(): BelongsTo
    {
        return $this->belongsTo(Scene::class);
    }

    public function defaultTexture(): BelongsTo
    {
        return $this->belongsTo(Texture::class, 'default_texture_id');
    }

    protected function mask(): Attribute
    {
        return new Attribute(get: fn () => $this->getFirstMedia(self::MASK)?->original_url);
    }
}
