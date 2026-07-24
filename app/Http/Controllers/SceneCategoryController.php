<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\SceneCategoryResource;
use App\Models\SceneCategory;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SceneCategoryController
{
    public function index(): AnonymousResourceCollection
    {
        return SceneCategoryResource::collection(SceneCategory::all());
    }
}
