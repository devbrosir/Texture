<?php

declare(strict_types=1);

namespace Modules\User\Enums;

enum Role: int
{
    case USER = 1;
    case ADMIN = 2;
}
