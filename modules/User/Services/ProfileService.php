<?php

declare(strict_types=1);

namespace Modules\User\Services;

use Illuminate\Support\Arr;
use Modules\User\Models\User;

class ProfileService
{
    public function __construct(private readonly UserService $userService) {}

    public function update(User $user, array $data): User
    {
        $this->userService->updateUser($user, Arr::only($data, ['name', 'birthday', 'gender']));

        return $user;
    }
}
