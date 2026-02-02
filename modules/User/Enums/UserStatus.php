<?php

declare(strict_types=1);

namespace Modules\User\Enums;

use Modules\Base\Traits\Enum\EnumTranslatable;

enum UserStatus: int
{
    use EnumTranslatable;

    case INACTIVE = 1;
    case ACTIVE = 2;
    case BLOCKED = 3;
}
