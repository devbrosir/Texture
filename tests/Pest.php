<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Sleep;
use Illuminate\Support\Str;
use Modules\User\Models\User;
use Tests\TestCase;

use function Pest\Laravel\withHeaders;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->beforeEach(function (): void {
        Str::createRandomStringsNormally();
        Str::createUuidsNormally();
        Http::preventStrayRequests();
        Process::preventStrayProcesses();
        Sleep::fake();

        $this->freezeTime();
    })
    ->in('Browser', 'Feature', 'Unit');

expect()->extend('toBeOne', fn () => $this->toBe(1));

function withUser(User $user): Illuminate\Foundation\Testing\TestCase
{
    $token = $user->createToken('test')->plainTextToken;

    return withHeaders([
        'Authorization' => 'Bearer '.$token,
        'Accept' => 'application/json',
    ]);
}
