<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Part;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Part */
final class PartResource extends JsonResource
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
            'back_url' => $this->_scene['image'] ?? $this->scene->image,
            'scene_title' => $this->_scene['title'] ?? $this->scene->title,
            'mask_url' => $this->mask,
            'version' => $this->version,
            'texture_id' => $this->default_texture_id,
            'mask_config' => $this->mask_config,
            'type' => $this->type->value,
        ];
    }
}
