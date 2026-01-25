<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Collection;

final class BannerController
{
    public function index(): Collection
    {
        return Banner::query()->where('active', true)->get()->append('image');
    }
}
