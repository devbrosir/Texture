<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TextureCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read string $title
 * @property-read int $type_id
 * @property-read TextureType $type
 */
class TextureCategory extends Model
{
    /** @use HasFactory<TextureCategoryFactory> */
    use HasFactory;

    public static function tree(): array
    {
        return TextureType::with('categories')->get()->map(fn (TextureType $textureType): array => [
            'label' => $textureType->title,
            'value' => 't-'.$textureType->id,
            'children' => $textureType->categories->map(fn (TextureCategory $category): array => [
                'label' => $category->title,
                'value' => $category->id,
            ])->values()->all(),
        ])->all();
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TextureType::class, 'type_id');
    }
}
