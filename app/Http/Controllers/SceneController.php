<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\SceneResource;
use App\Models\Part;
use App\Models\Scene;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

final class SceneController
{
    public function __construct()
    {
        Scene::addGlobalScope(fn ($query) => $query->where('active', true));
        Part::addGlobalScope(fn ($query) => $query->where('active', true));
    }

    public function index(): AnonymousResourceCollection
    {
        return SceneResource::collection(Scene::query()->withCount('parts')->paginate(20));
    }

    public function show(Scene $scene): SceneResource
    {
        $scene->load('parts');

        return new SceneResource($scene);
    }
}
