<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Auth\Enums\OtpChannel;
use Modules\Auth\Exceptions\InvalidOtpException;
use Modules\Auth\Exceptions\OtpRateLimitException;
use Modules\Auth\Facades\Authenticator;
use Modules\Auth\Models\Otp;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    //
});

it('registers and sends otp successfully', function (): void {
    // Expect sendOtp called with SMS and provided mobile and extras
    Authenticator::shouldReceive('sendOtp')
        ->once()
        ->withArgs(fn ($channel, $mobile, $extras = null): bool => $channel === OtpChannel::SMS
            && $mobile === '09123456789'
            && is_array($extras)
            && $extras['password'] === 'secret')
        ->andReturnNull();

    $this->postJson('/api/v1/auth/register', [
        'mobile' => '09123456789',
        'password' => 'secret',
        'password_confirmation' => 'secret',
    ])->assertSuccessful();
});

it('returns 429 when register is rate limited', function (): void {
    Authenticator::shouldReceive('sendOtp')
        ->once()
        ->andThrow(new OtpRateLimitException());

    $this->postJson('/api/v1/auth/register', [
        'mobile' => '09123456789',
        'password' => 'secret',
        'password_confirmation' => 'secret',
    ])->assertTooManyRequests();
});

it('verifies register & creates user', function (): void {
    // Create a mock (or real) Otp model instance to satisfy return type
    $otp = Otp::query()->create([
        'identifier_field' => 'mobile',
        'identifier_value' => '09123456789',
        'code' => '123456',
        'expires_at' => now()->addMinute(),
        'extra' => [
            'password' => bcrypt('plain-password'),
        ],
    ]);

    $response = $this->postJson('/api/v1/auth/verify-register', [
        'mobile' => '09123456789',
        'code' => '123456',
        'name' => 'test',
    ]);

    $response->assertSuccessful()
        ->assertJsonStructure([
            'data' => [
                'user' => ['id', 'name', 'status'],
                'token' => ['token_type', 'access_token'],
            ],
        ]);

    // Assert that a user record with generated username exists.
    $this->assertDatabaseHas('users', ['name' => 'test']);

});

it('returns 401 on verify-register when otp invalid', function (): void {
    Authenticator::shouldReceive('verifyOtp')
        ->once()
        ->andThrow(new InvalidOtpException());

    $this->postJson('/api/v1/auth/verify-register', [
        'mobile' => '09123456789',
        'code' => '654321',
        'name' => 'test',
    ])->assertUnauthorized();
});
