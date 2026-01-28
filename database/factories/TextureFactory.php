<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Texture;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Texture>
 */
final class TextureFactory extends Factory
{
    protected $model = Texture::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'part_id' => 1,
        ];
    }
}
