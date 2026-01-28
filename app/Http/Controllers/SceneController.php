<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Scene;
use Illuminate\Pagination\LengthAwarePaginator;

final class SceneController
{
    public function __construct()
    {
        Scene::addGlobalScope(fn ($query) => $query->where('active', true));
        Part::addGlobalScope(fn ($query) => $query->where('active', true));
    }

    public function index(): LengthAwarePaginator
    {
        return Scene::query()->withCount('parts')->paginate(20);
    }

    public function show(Scene $scene): Scene
    {
        $scene->load('media', 'parts.textures.media')->makeHidden('media')
            ->append('image');
        $scene->parts->each(fn (Part $part) => $part->textures->makeHidden('media')->append('image'));

        return $scene;
    }
}
