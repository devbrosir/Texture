<?php

declare(strict_types=1);

namespace Modules\User\Enums;

enum UserStatus: int
{
    case INACTIVE = 1;
    case ACTIVE = 2;
    case BLOCKED = 3;
}
