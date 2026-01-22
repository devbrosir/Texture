<?php

declare(strict_types=1);

namespace Modules\User\Http\Controllers\Api\v1;

use Modules\User\Http\Requests\Profile\UpdateProfileRequest;
use Modules\User\Http\Resources\ProfileResource;
use Modules\User\Models\User;
use Modules\User\Services\ProfileService;

final class ProfileController
{
    public function user(): User
    {
        return auth()->user();
    }

    public function show(): ProfileResource
    {
        return ProfileResource::make(user());
    }

    public function update(UpdateProfileRequest $request, ProfileService $service): ProfileResource
    {
        $user = $service->update(user(), $request->validated());

        return ProfileResource::make($user);
    }

    public function logout(): void
    {
        auth()->logout();
    }
}
