<?php

declare(strict_types=1);

namespace Modules\User\Services;

use Modules\User\Models\User;

class UserService
{
    public function updateUser(User $user, array $data): void
    {
        $user->update($data);
    }
}
