<?php

declare(strict_types=1);

namespace Modules\User\Services;

use Illuminate\Support\Facades\DB;
use Modules\Auth\Models\Otp;
use Modules\User\Enums\Role;
use Modules\User\Enums\UserStatus;
use Modules\User\Models\User;

readonly class RegistrationService
{
    public function verifyRegistration(array $data, Otp $otp): User
    {
        DB::transaction(function () use (&$user, $data, $otp): void {

            $user = User::query()->create([
                'mobile' => $data['mobile'],
                'name' => $data['name'],
                'password' => $otp->extra['password'],
                'status' => UserStatus::ACTIVE,
                'role' => Role::USER->value,
            ]);
        });

        return $user;
    }
}
