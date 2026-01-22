<?php

declare(strict_types=1);

namespace Modules\User\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Modules\User\Enums\Role;
use Modules\User\Enums\UserStatus;
use Modules\User\Models\User;

/**
 * @extends Factory<User>
 */
final class UserFactory extends Factory
{
    protected $model = User::class;

    private static ?string $password = null;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => null,
            'mobile' => '09'.$this->faker->numberBetween(100000000, 999999999),
            'role' => $this->faker->randomElement(Role::cases())->value,
            'status' => UserStatus::ACTIVE->value,
            'birthday' => $this->faker->date(),
            'gender' => $this->faker->numberBetween(1, 2),
            'password' => self::$password ??= Hash::make('password'),
        ];
    }

    public function active(): self
    {
        return $this->state(fn (array $attributes): array => [
            'status' => UserStatus::ACTIVE->value,
        ]);
    }

    public function admin(): self
    {
        return $this->state(fn (array $attributes): array => [
            'role' => Role::ADMIN->value,
            'status' => UserStatus::ACTIVE->value,
        ]);
    }

    public function verified(): self
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => now(),
            'status' => UserStatus::ACTIVE->value,
        ]);
    }

    public function unverified(): self
    {
        return $this->state(fn (array $attributes): array => [
            'email_verified_at' => null,
        ]);
    }
}
