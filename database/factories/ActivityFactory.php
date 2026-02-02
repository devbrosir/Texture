<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ActivityType;
use App\Models\Activity;
use App\Models\Scene;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Activity>
 */
class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->randomElement([1, 2, null]),
            'type' => $this->faker->randomElement(ActivityType::cases()),
            'related_type' => Scene::class,
            'related_id' => $this->faker->numberBetween(1, 10),
            'metadata' => [
                'download_format' => 'png',
                'scene_id' => 25,
            ],
        ];
    }
}
