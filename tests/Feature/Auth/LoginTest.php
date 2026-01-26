<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Modules\Auth\Enums\OtpChannel;
use Modules\Auth\Exceptions\InvalidOtpException;
use Modules\Auth\Exceptions\OtpRateLimitException;
use Modules\Auth\Facades\Authenticator;
use Modules\Auth\Models\Otp;
use Modules\User\Models\User;

use function Pest\Laravel\assertDatabaseHas;

it('sends otp successfully', function (): void {
    $user = User::factory()->create(['mobile' => '09123456789']);

    Authenticator::shouldReceive('sendOtpToUser')
        ->once()
        ->withSomeOfArgs(OtpChannel::SMS)
        ->andReturnNull();

    $this->postJson('/api/v1/auth/send-otp', [
        'mobile' => '09123456789',
    ])->assertSuccessful();
});

it('returns 429 when sendOtp rate limited', function (): void {
    User::factory()->create(['mobile' => '09123456789']);

    Authenticator::shouldReceive('sendOtpToUser')
        ->once()
        ->withSomeOfArgs(OtpChannel::SMS)
        ->andThrow(new OtpRateLimitException());

    $this->postJson('/api/v1/auth/send-otp', [
        'mobile' => '09123456789',
    ])->assertTooManyRequests();
});

it('verifies otp successfully', function (): void {
    $user = User::factory()->create(['mobile' => '09123456789']);
    Otp::query()->create([
        'identifier_field' => 'mobile',
        'identifier_value' => '09123456789',
        'user_id' => $user->id,
        'code' => '123456',
        'expires_at' => now()->addMinutes(10),
    ]);

    $this->postJson('/api/v1/auth/verify', [
        'mobile' => '09123456789',
        'code' => '123456',
    ])->assertOk()
        ->assertJsonPath('data.user.name', $user->name);

    assertDatabaseHas('otps', ['user_id' => $user->id, 'retries' => 1]);
});

it('returns 401 when verifyOtp is invalid', function (): void {
    Authenticator::shouldReceive('verifyOtp')
        ->once()
        ->andThrow(new InvalidOtpException());

    $this->postJson('/api/v1/auth/verify', [
        'mobile' => '09123456789',
        'code' => '654321',
    ])->assertUnauthorized();
});

it('logs in successfully and returns token payload', function (): void {
    // Create a user model in DB for credentials check if needed, or return a mock user object.
    $user = User::factory()->create([
        'mobile' => '09123456789',
        'password' => bcrypt('password123'),
    ]);

    Authenticator::shouldReceive('checkCredentials')
        ->once()
        ->withSomeOfArgs('09123456789', 'password123')
        ->andReturn($user);

    Authenticator::shouldReceive('loginUserAndIssueToken')
        ->once()
        ->with($user)
        ->andReturn(['token' => 'test-token', 'type' => 'bearer']);

    $this->postJson('/api/v1/auth/login', [
        'mobile' => '09123456789',
        'password' => 'password123',
    ])->assertJsonFragment(['token' => 'test-token']);
});

it('returns 401 when login credentials are invalid', function (): void {
    Authenticator::shouldReceive('checkCredentials')
        ->once()
        ->andReturnFalse();

    $this->postJson('/api/v1/auth/login', [
        'mobile' => '09123456789',
        'password' => '654321',
    ])->assertUnauthorized();
});

it('login via wordpress', function (): void {
    Http::fake([
        env('WP_URL').'/wp-json/sso/v1/verify' => Http::response([
            'success' => true,
            'data' => [
                'id' => 12,
                'email' => 'test@example.com',
                'name' => 'ali',
                'first_name' => 'Ali',
                'last_name' => 'Alavi',
                'display_name' => 'TestUser',
                'username' => 'alo123',
            ],
        ]),
    ]);

    $this->postJson('/api/v1/auth/wp-login', [
        'token' => 'fake-temp-token',
    ])->assertOk();

    $this->assertDatabaseHas('users', [
        'wp_id' => 12,
        'email' => 'test@example.com',
    ]);

});
