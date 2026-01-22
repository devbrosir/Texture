<?php

declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Support\Facades\Hash;
use Mockery;
use Modules\Auth\Enums\OtpChannel;
use Modules\Auth\Exceptions\InvalidOtpException;
use Modules\Auth\Exceptions\OtpRateLimitException;
use Modules\Auth\Facades\Authenticator;
use Modules\Auth\Models\Otp;
use Modules\User\Models\User;

use function Pest\Laravel\postJson;

it('sends otp for password reset successfully', function () {
    $user = User::factory()->create(['mobile' => '09123456789']);

    Authenticator::shouldReceive('sendOtpToUser')
        ->once()
        ->with(OtpChannel::SMS, Mockery::type(User::class));

    postJson('/api/v1/auth/forget-pass-send-otp', [
        'mobile' => '09123456789',
    ])->assertOk();
});

it('returns 422 if user not found during forget password', function () {
    postJson('/api/v1/auth/forget-pass-send-otp', [
        'mobile' => '09990000000',
    ])->assertStatus(422);
});

it('returns 429 when forget password otp is rate limited', function () {
    $user = User::factory()->create(['mobile' => '09123456789']);

    Authenticator::shouldReceive('sendOtpToUser')
        ->once()
        ->andThrow(new OtpRateLimitException());

    postJson('/api/v1/auth/forget-pass-send-otp', [
        'mobile' => '09123456789',
    ])->assertStatus(429);
});

it('resets password successfully with valid otp', function () {
    $user = User::factory()->create([
        'mobile' => '09123456789',
        'password' => Hash::make('old_password'),
    ]);

    // simulate OTP confirmation
    $otp = new Otp([
        'identifier_value' => '09123456789',
        'code' => '123456',
    ]);
    $otp->user()->associate($user);

    Authenticator::shouldReceive('verifyOtp')
        ->once()
        ->with(OtpChannel::SMS, '09123456789', '123456')
        ->andReturn($otp);

    $response = postJson('/api/v1/auth/reset-pass', [
        'mobile' => '09123456789',
        'code' => '123456',
        'password' => 'new_password_123',
        'password_confirmation' => 'new_password_123',
    ]);

    $response->assertOk();

    $user->refresh();
    expect(Hash::check('new_password_123', $user->password))->toBeTrue();
});

it('fails to reset password with invalid otp', function () {
    Authenticator::shouldReceive('verifyOtp')
        ->once()
        ->andThrow(new InvalidOtpException());

    postJson('/api/v1/auth/reset-pass', [
        'mobile' => '09123456789',
        'code' => '123456',
        'password' => 'new_password_123',
        'password_confirmation' => 'new_password_123',
    ])->assertStatus(401);
});
