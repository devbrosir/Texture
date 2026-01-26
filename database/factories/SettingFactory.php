<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Setting>
 */
final class SettingFactory extends Factory
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
            'key' => $this->faker->word(),
            'value' => $this->faker->boolean() ? $this->setting() : $this->arrayOfSettings(),
        ];
    }

    private function setting(): int|bool|string
    {
        $rnd = $this->faker->numberBetween(1, 3);

        return match ($rnd) {
            1 => $this->faker->numberBetween(0, 100),
            2 => $this->faker->boolean(),
            3 => $this->faker->word(),
        };
    }

    private function arrayOfSettings(): array
    {
        $size = $this->faker->numberBetween(1, 5);
        $setting = [];
        for ($i = 1; $i <= $size; $i++) {
            $key = $this->faker->word();
            $setting[$key] = $this->setting();
        }

        return $setting;
    }
}
