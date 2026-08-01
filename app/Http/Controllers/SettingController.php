<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

final class SettingController
{
    public function index(): array
    {
        $settings = Setting::all();
        $k = $settings->where('key', 'seo')->keys()->first();
        $seo = $settings->get($k)->toArray();
        if ($img = Arr::get($seo, 'value.og.image')) {
            Arr::set($seo, 'value.og.image', Storage::disk('public')->url($img));
        }
        $settings = $settings->toArray();
        Arr::set($settings, $k, $seo);

        return $settings;
    }
}
