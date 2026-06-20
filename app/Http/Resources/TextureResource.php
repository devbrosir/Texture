<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Texture;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Texture */
final class TextureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'width' => $this->width,
            'height' => $this->height,
            'version' => $this->version,
            'url' => $this->image,
            'thumbnail' => $this->thumbnail,
        ];
    }
}
