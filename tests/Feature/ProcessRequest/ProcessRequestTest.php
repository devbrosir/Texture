<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RequestStatus;
use App\Models\ProcessRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Upload\Models\Dummy;
use Modules\User\Models\User;

use function Pest\Laravel\assertDatabaseHas;

it('lists only the authenticated user process requests', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    ProcessRequest::factory()->count(3)->create(['user_id' => $user->id]);
    ProcessRequest::factory()->count(2)->create(['user_id' => $otherUser->id]);

    withUser($user)
        ->getJson('/api/v1/requests')
        ->assertOk()
        ->assertJsonCount(3, 'data.list');
});

it('stores a new process request successfully', function (): void {
    $user = User::factory()->create();
    Storage::fake('public');
    $file = UploadedFile::fake()->image('img.jpg');
    $media = Dummy::instance()->addMedia($file)->toMediaCollection('temp');

    $data = [
        'description' => 'Test description',
        'images' => [$media->uuid],
    ];

    withUser($user)
        ->postJson('/api/v1/requests', $data)
        ->assertJsonStructure([
            'data' => [
                'description', 'images', 'user_id',
            ],
        ])
        ->assertOk();

    assertDatabaseHas('process_requests', [
        'user_id' => $user->id,
        'description' => 'Test description',
    ]);
});

it('cancels the process request if owned by the user', function (): void {
    $user = User::factory()->create();
    $processRequest = ProcessRequest::factory()->create([
        'user_id' => $user->id,
        'status' => RequestStatus::PENDING,
    ]);

    withUser($user)
        ->postJson("/api/v1/requests/$processRequest->id/cancel")
        ->assertOk();

    expect($processRequest->fresh()->status)->toBe(RequestStatus::CANCELED);
});

it('returns 404 when canceling a process request owned by another user', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();
    $processRequest = ProcessRequest::factory()->create([
        'user_id' => $otherUser->id,
        'status' => RequestStatus::PENDING,
    ]);

    withUser($user)
        ->postJson("/api/v1/requests/$processRequest->id/cancel")
        ->assertNotFound();

    expect($processRequest->fresh()->status)->toBe(RequestStatus::PENDING);
});

it('sets processed_at when status is updated to completed', function (): void {
    $processRequest = ProcessRequest::factory()->create([
        'status' => RequestStatus::PENDING,
        'processed_at' => null,
    ]);

    $processRequest->update(['status' => RequestStatus::COMPLETED]);

    expect($processRequest->fresh()->processed_at)->not->toBeNull();
});
