<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ProcessRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProcessRequest>
 */
final class ProcessRequestFactory extends Factory
{
    protected $model = ProcessRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'description' => fake()->text(),
        ];
    }
}
