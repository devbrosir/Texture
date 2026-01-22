<?php

declare(strict_types=1);

namespace Tests\Feature\Profile;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\User\Enums\Gender;
use Modules\User\Enums\Role;
use Modules\User\Models\User;

uses(RefreshDatabase::class);

it('can get user', function (): void {
    $this->user = User::factory()->create(['role' => Role::USER->value]);
    withUser($this->user)->getJson('/api/v1/profile/user')
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'name', 'birthday', 'gender',
            ],
        ]);
});

it('can get profile', function (): void {
    // Arrange
    $this->user = User::factory()->create(['role' => Role::USER->value]);
    $this->user->update([
        'name' => 'علی احمدی',
        'birthday' => '1990-05-15',
        'gender' => Gender::MALE,
    ]);

    // Act
    $response = withUser($this->user)->getJson('/api/v1/profile');

    // Assert
    $response
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'name', 'birthday', 'gender',
            ],
        ])
        ->assertJson([
            'data' => [
                'name' => 'علی احمدی',
                'birthday' => '1990-05-15',
            ],
        ]);
});

it('can update profile', function (): void {
    // Arrange
    $this->user = User::factory()->create(['role' => Role::USER->value]);

    $updateData = [
        'name' => 'علی رضا احمدی',
        'birthday' => '1995-03-20',
        'gender' => Gender::MALE->value,
    ];

    // Act
    $response = withUser($this->user)->putJson('/api/v1/profile', $updateData);

    // Assert
    $response->assertOk();

    $this->assertDatabaseHas('users', [
        'id' => $this->user->id,
        'name' => 'علی رضا احمدی',
        'birthday' => '1995-03-20',
        'gender' => Gender::MALE->value,
    ]);

    $response->assertJsonStructure([
        'data' => [
            'name', 'birthday', 'gender',
        ],
    ]);
});

it('cannot update profile without auth', function (): void {
    $this->putJson('/api/v1/profile', ['name' => 'test'])
        ->assertUnauthorized();
});

it('validation fails for invalid data', function (): void {
    $this->user = User::factory()->create(['role' => Role::USER->value]);
    withUser($this->user)->putJson('/api/v1/profile', [
        'name' => null,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);
});
