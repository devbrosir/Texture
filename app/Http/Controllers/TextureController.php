<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\TextureResource;
use App\Models\Texture;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class TextureController
{
    public function index(): AnonymousResourceCollection
    {
        if (request('ids')) {
            return TextureResource::collection(Texture::query()->whereIn('id', request('ids'))->limit(50)->get());
        }

        return TextureResource::collection(Texture::query()->paginate());
    }
}
