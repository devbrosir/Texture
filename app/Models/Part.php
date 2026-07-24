<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TextureType;
use App\MediaLibrary\HasCustomConversions;
use App\Traits\HasVersion;
use Database\Factories\PartFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Base\Support\BaseModel;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property-read string $title
 * @property-read bool $active
 * @property-read int $scene_id
 * @property-read int $default_texture_id
 * @property-read int $version
 * @property-read array $mask_config
 * @property-read TextureType $type
 * @property-read array $selected_types
 * @property-read string $mask
 * @property-read string $thumbnail
 * @property-read array $groupedTextures
 * @property-read Scene $scene
 */
final class Part extends BaseModel implements HasMedia
{
    use HasCustomConversions;

    /** @use HasFactory<PartFactory> */
    use HasFactory;

    use HasVersion;
    use InteractsWithMedia;

    public const string MASK = 'MASK';

    protected $casts = [
        'active' => 'boolean',
        'mask_config' => 'array',
        'type' => TextureType::class,
        'selected_types' => 'array',
    ];

    public function registerCustomConversions(): void
    {
        $this->addCustomConversion('thumbnail')->width(150)->height(150);
    }

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
        return new Attribute(get: fn (): string => $this->getFirstMediaUrl(self::MASK));
    }

    protected function thumbnail(): Attribute
    {
        return new Attribute(get: fn (): ?string => $this->getCustomConversionUrl(self::MASK, 'thumbnail'));
    }

    protected function groupedTextures(): Attribute
    {
        return Attribute::make(get: fn (): array => \App\Models\TextureType::with('categories')
            ->whereHas('categories', fn (Builder $q) => $q->whereIn('id', $this->selected_types))
            ->get()
            ->map(fn (\App\Models\TextureType $textureType): array => [
                'id' => $textureType->id,
                'title' => $textureType->title,
                'categories' => $textureType->categories->whereIn('id', $this->selected_types)->values()
                    ->map(fn (TextureCategory $cat) => $cat->only('id', 'title'))->all(),
            ])->all());
    }

    protected function getVersionableFields(): array
    {
        return ['mask_config'];
    }

    protected function getVersionableMediaCollection(): string
    {
        return self::MASK;
    }
}
