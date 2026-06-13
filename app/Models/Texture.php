<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TextureFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Base\Support\BaseModel;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property-read string $title
 * @property-read int $width
 * @property-read int $height
 * @property-read string $image
 * @property-read string $thumbnail
 */
final class Texture extends BaseModel implements HasMedia
{
    /** @use HasFactory<TextureFactory> */
    use HasFactory;

    use InteractsWithMedia;

    // collection name
    public const string TEXTURE = 'texture';

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')->width(100)->height(100);
    }

    protected function image(): Attribute
    {
        return new Attribute(get: fn (): string => $this->getFirstMediaUrl(self::TEXTURE));
    }

    protected function thumbnail(): Attribute
    {
        return new Attribute(get: fn (): string => $this->getFirstMediaUrl(self::TEXTURE, 'thumbnail'));
    }
}
