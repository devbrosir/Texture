<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\BannerFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Base\Traits\HasDateTimeCast;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property-read string $link
 * @property-read int $delay
 * @property-read bool $active
 * @property-read int $show_count
 * @property-read string $image
 */
final class Banner extends Model implements HasMedia
{
    use HasDateTimeCast;

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
