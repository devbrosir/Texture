<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\BannerFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Base\Support\BaseModel;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property-read string $link
 * @property-read int $delay
 * @property-read bool $active
 * @property-read int $show_count
 * @property-read string $image
 */
final class Banner extends BaseModel implements HasMedia
{

    /** @use HasFactory<BannerFactory> */
    use HasFactory;

    use InteractsWithMedia;

    public const string IMAGE = 'image';

    public $relations = ['media'];

    public $hidden = ['media', 'created_at', 'updated_at'];

    public function image(): Attribute
    {
        return Attribute::make(fn () => $this->getFirstMedia(self::IMAGE)?->original_url);
    }
}
