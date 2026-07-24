<?php

declare(strict_types=1);

namespace App\Models;

use App\MediaLibrary\HasCustomConversions;
use App\Traits\HasVersion;
use Database\Factories\TextureFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Base\Support\BaseModel;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property-read string $title
 * @property-read int $width
 * @property-read int $height
 * @property-read int $version
 * @property-read null|string $color
 * @property-read int $type_id
 * @property-read int $category_id
 * @property-read null|array $tags
 * @property-read null|string $product_url
 * @property-read string $image
 * @property-read string $thumbnail
 * @property-read TextureType $type
 * @property-read TextureCategory $category
 */
final class Texture extends BaseModel implements HasMedia
{
    use HasCustomConversions;

    /** @use HasFactory<TextureFactory> */
    use HasFactory;

    use HasVersion;
    use InteractsWithMedia;

    // collection name
    public const string TEXTURE = 'texture';

    protected $casts = [
        'tags' => 'array',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(TextureType::class, 'type_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TextureCategory::class, 'category_id');
    }

    public function registerCustomConversions(): void
    {
        $this->addCustomConversion('thumbnail')->width(150)->height(150);
    }

    protected function image(): Attribute
    {
        return new Attribute(get: fn (): string => $this->getFirstMediaUrl(self::TEXTURE));
    }

    protected function thumbnail(): Attribute
    {
        return new Attribute(get: fn (): ?string => $this->getCustomConversionUrl(self::TEXTURE, 'thumbnail'));
    }

    protected function getVersionableFields(): array
    {
        return ['width', 'height'];
    }

    protected function getVersionableMediaCollection(): string
    {
        return self::TEXTURE;
    }
}
