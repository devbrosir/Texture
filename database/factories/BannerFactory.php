<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Banner>
 */
final class BannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'link' => $this->faker->url(),
            'delay' => $this->faker->numberBetween(30, 60),
            'active' => $this->faker->numberBetween(0, 1),
            'show_count' => $this->faker->numberBetween(1, 5),
        ];
    }
}
