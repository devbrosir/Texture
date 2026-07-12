<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

final class FileController
{
    public function show(string $uuid, string $name)
    {
        return $this->serveFile("$uuid/$name");
    }

    public function showConversions(string $uuid, string $name)
    {
        return $this->serveFile("$uuid/conversions/$name");
    }

    private function serveFile(string $path)
    {
        abort_unless(Storage::disk('public')->exists($path), 404);

        $headers = [
            'Cache-Control' => 'public, max-age=2592000, immutable',
            'Access-Control-Allow-Origin' => '*',
        ];

        return Storage::disk('public')->response($path, null, $headers);
    }
}
