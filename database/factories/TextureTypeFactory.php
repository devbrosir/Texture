<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\TextureType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TextureType>
 */
class TextureTypeFactory extends Factory
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
