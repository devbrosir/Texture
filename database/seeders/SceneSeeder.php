<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Part;
use App\Models\Scene;
use App\Models\Texture;
use Illuminate\Database\Seeder;

final class SceneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Scene::factory(10)
            ->has(Part::factory(3)->has(Texture::factory(2)))
            ->create();
    }
}
