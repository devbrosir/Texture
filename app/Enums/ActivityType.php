<?php

declare(strict_types=1);

namespace App\Enums;

use Modules\Base\Traits\Enum\EnumTranslatable;

enum ActivityType: string
{
    use EnumTranslatable;

    case LOGIN_BY_PASS = 'login_by_pass';
    case LOGIN_BY_OTP = 'login_by_otp';
    case REGISTER = 'register';
    case APPLY_TEXTURE = 'apply_texture';
    case DOWNLOAD_SCENE = 'download_scene';
    case SEND_REQUEST = 'send_request';
    case CANCEL_REQUEST = 'cancel_request';
}
