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
            'back_url' => $this->scene->image,
            'mask_url' => $this->mask,
            'texture_id' => $this->default_texture_id,
            'mask_config' => $this->mask_config,
        ];
    }
}
