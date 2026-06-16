<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

final class FileController
{
    public function show(string $uuid, string $name)
    {
        $path = "$uuid/$name";

        abort_unless(Storage::disk('public')->exists($path), 404);

        $headers = [
            'Cache-Control' => 'public, max-age=2592000, immutable',
        ];

        return Storage::disk('public')->response($path, null, $headers);
    }
}
