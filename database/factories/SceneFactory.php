<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Scene;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Scene>
 */
final class SceneFactory extends Factory
{
    protected $model = Scene::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'tags' => $this->faker->words(),
        ];
    }
}
