<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\SceneCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SceneCategory>
 */
class SceneCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
        ];
    }
}
