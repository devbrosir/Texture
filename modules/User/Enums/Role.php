<?php

declare(strict_types=1);

namespace Modules\User\Enums;

use Modules\Base\Traits\Enum\EnumTranslatable;

enum Role: int
{
    use EnumTranslatable;

    case USER = 1;
    case ADMIN = 2;
}
