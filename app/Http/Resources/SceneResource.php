<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Scene;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Scene */
final class SceneResource extends JsonResource
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
            'tags' => $this->tags,
            'active' => $this->active,
            'back_url' => $this->image,
            'thumbnail' => $this->thumbnail,
            'version' => $this->version,
            'parts_count' => $this->whenCounted('parts'),
            'parts' => $this->relationLoaded('parts')
                ? PartResource::collection(
                    $this->parts->each(fn ($part) => $part->setAttribute(
                        '_scene', ['title' => $this->title, 'image' => $this->image]
                    ))
                ) : null,
        ];
    }
}
