<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\SceneCategoryFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property-read string $title
 * @property-read string|null $image
 */
class SceneCategory extends Model implements HasMedia
{
    /** @use HasFactory<SceneCategoryFactory> */
    use HasFactory;

    use InteractsWithMedia;

    public const string IMAGE = 'category_image';

    protected function image(): Attribute
    {
        return Attribute::make(get: fn (): string => $this->getFirstMediaUrl(self::IMAGE));
    }
}
