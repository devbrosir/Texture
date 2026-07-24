<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TextureTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property-read string $title
 * @property-read Collection<TextureCategory> $categories
 */
class TextureType extends Model
{
    /** @use HasFactory<TextureTypeFactory> */
    use HasFactory;

    public function categories(): HasMany
    {
        return $this->hasMany(TextureCategory::class, 'type_id');
    }
}
