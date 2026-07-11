<?php

declare(strict_types=1);

return [
    'request_statuses' => [
        'PENDING' => 'در حال بررسی',
        'COMPLETED' => 'کامل شده',
        'CANCELED' => 'لغو شده',
    ],

    'activity_types' => [
        'LOGIN_BY_PASS' => 'ورود (پسورد)',
        'LOGIN_BY_OTP' => 'ورود (کد تایید)',
        'REGISTER' => 'ثبت نام',
        'APPLY_TEXTURE' => 'اعمال تکسچر',
        'DOWNLOAD_SCENE' => 'دانلود محیط',
        'SEND_REQUEST' => 'ارسال درخواست محیط',
        'CANCEL_REQUEST' => 'لغو درخواست',
    ],

    'texture_types' => [
        'FLOOR' => 'کف',
        'WALL' => 'دیوار',
    ],
];
