<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Enums\Role;
use Modules\User\Models\User;

final class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->create([
            'name' => 'Mr Bavandi',
            'email' => 'admin@example.com',
            'mobile' => '09399118421',
            'role' => Role::ADMIN->value,
            'password' => bcrypt(env('ADMIN_PASSWORD')),
        ]);
    }
}
