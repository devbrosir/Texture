<?php

declare(strict_types=1);

use Modules\User\Models\User;

/** @noinspection PhpIncompatibleReturnTypeInspection */
function user(): ?User
{
    return auth()->user();
}
