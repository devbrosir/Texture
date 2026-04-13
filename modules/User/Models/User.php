<?php

declare(strict_types=1);

namespace Modules\User\Models;

use Carbon\CarbonInterface;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Base\Traits\HasDateTimeCast;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Enums\Gender;
use Modules\User\Enums\Role;
use Modules\User\Enums\UserStatus;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $email
 * @property-read CarbonInterface|null $email_verified_at
 * @property-read string $mobile
 * @property-read Role $role
 * @property-read UserStatus $status
 * @property-read int|null $wp_id
 * @property-read CarbonInterface|null $birthday
 * @property-read Gender|null $gender
 * @property-read string $password
 * @property-read string|null $remember_token
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
final class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    use HasApiTokens;
    use HasDateTimeCast;

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'id' => 'integer',
            'mobile' => 'string',
            'email' => 'string',
            'email_verified_at' => 'datetime',
            'role' => Role::class,
            'status' => UserStatus::class,
            'birthday' => 'date:Y-m-d',
            'gender' => Gender::class,
            'password' => 'hashed',
            'remember_token' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === Role::ADMIN;
    }

    public function isUser(): bool
    {
        return $this->role === Role::USER;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }
}
