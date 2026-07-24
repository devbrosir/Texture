<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SceneCategory;
use App\Models\TextureCategory;
use App\Models\TextureType;
use Illuminate\Database\Seeder;

final class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TextureType::query()->truncate();
        TextureCategory::query()->truncate();
        SceneCategory::query()->truncate();

        TextureType::factory(5)->create();
        $type = TextureType::factory()->create();
        TextureCategory::factory(4)->create(['type_id' => $type->id]);
        TextureCategory::factory(5)->create();
        SceneCategory::factory(7)->create();
    }
}
