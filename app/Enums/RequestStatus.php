<?php

declare(strict_types=1);

namespace App\Enums;

use Modules\Base\Traits\Enum\EnumTranslatable;

enum RequestStatus: int
{
    use EnumTranslatable;

    case PENDING = 1;
    case COMPLETED = 2;
    case CANCELED = 3;
}
