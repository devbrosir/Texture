<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Scene;

class SceneService
{
    public function setMaskData(Scene $scene, array $data): Scene
    {
        $scene->update(['mask' => $data]);

        return $scene;
    }
}
