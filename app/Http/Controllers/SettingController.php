<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;

final class SettingController
{
    public function index(): Collection
    {
        return Setting::all();
    }
}
