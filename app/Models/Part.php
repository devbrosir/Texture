<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PartFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Modules\Base\Support\BaseModel;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property-read string $title
 * @property-read bool $active
 * @property-read int $scene_id
 * @property-read Scene $scene
 * @property-read Collection<Media> $textures
 */
final class Part extends BaseModel
{
    /** @use HasFactory<PartFactory> */
    use HasFactory;

    protected $casts = [
        'active' => 'boolean',
    ];

    public function scene(): BelongsTo
    {
        return $this->belongsTo(Scene::class);
    }

    public function textures(): HasMany
    {
        return $this->hasMany(Texture::class);
    }
}
