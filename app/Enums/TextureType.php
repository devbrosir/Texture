<?php

declare(strict_types=1);

namespace App\Enums;

use Modules\Base\Traits\Enum\EnumTranslatable;

enum TextureType: int
{
    use EnumTranslatable;

    case FLOOR = 1;
    case WALL = 2;
}
