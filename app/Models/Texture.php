<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TextureFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Base\Support\BaseModel;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property-read string $title
 * @property-read int $part_id
 * @property-read Part $part
 * @property-read string $image
 */
final class Texture extends BaseModel implements HasMedia
{
    /** @use HasFactory<TextureFactory> */
    use HasFactory;

    use InteractsWithMedia;

    // collection name
    public const string TEXTURE = 'texture';

    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class);
    }

    protected function image(): Attribute
    {
        return new Attribute(get: fn () => $this->getFirstMedia(self::TEXTURE)?->original_url);
    }
}
