<?php

declare(strict_types=1);

namespace Modules\User\Services;

use Illuminate\Support\Facades\Hash;
use Modules\User\Enums\Role;
use Modules\User\Models\User;

class UserService
{
    public function updateUser(User $user, array $data): void
    {
        $user->update($data);
    }

    public function createWPUser(array $data): User
    {
        return User::query()->create([
            'name' => $data['name'] ?? ($data['first_name'].' '.$data['last_name']) ?? $data['display_name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'] ?? null,
            'wp_id' => $data['id'],
            'role' => Role::USER,
            'password' => Hash::make($data['username']),
        ]);
    }

    public function createUser(array $data): User
    {
        return User::query()->create([
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'mobile' => $data['mobile'] ?? null,
            'wp_id' => $data['wp_id'] ?? null,
            'role' => Role::USER,
        ]);
    }

    public function updateWPUser(User $user, array $data): void
    {
        $user->update([
            'name' => $data['name'] ?? ($data['first_name'].$data['last_name']) ?? $data['display_name'],
        ]);
    }

    public function userExists(array $conditions): bool
    {
        return User::query()->where($conditions)->exists();
    }

    public function getByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }
}
