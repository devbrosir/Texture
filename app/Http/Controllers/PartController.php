<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SetMaskDataRequest;
use App\Http\Resources\PartResource;
use App\Models\Part;
use App\Models\Scene;

final class PartController
{
    public function __construct()
    {
        Scene::addGlobalScope(fn ($query) => $query->where('active', true));
        Part::addGlobalScope(fn ($query) => $query->where('active', true));
    }

    public function show(Part $part): PartResource
    {
        return new PartResource($part);
    }

    public function setMaskData(Part $part, SetMaskDataRequest $request): PartResource
    {
        $part->update($request->validated());

        return new PartResource($part->refresh());
    }
}
