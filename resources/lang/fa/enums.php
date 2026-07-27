<?php

declare(strict_types=1);

return [
    'request_statuses' => [
        'PENDING' => 'در حال بررسی',
        'COMPLETED' => 'کامل شده',
        'CANCELED' => 'لغو شده',
    ],

    'activity_types' => [
        'ViewPage' => 'مشاهده سایت',
        'ViewBanner' => 'نمایش بنر',

        'ClickDetail' => 'کلیک روی جزئیات',
        'ClickProductLink' => 'کلیک روی لینک محصول',
        'ClickBanner' => 'کلیک روی بنر',
        'ClickLogin' => 'کلیک روی ورود',
        'ClickLoginSso' => 'کلیک روی ورود قبلی',

        'SelectPart' => 'انتخاب بخش',
        'SelectScene' => 'انتخاب محیط',
        'SelectTexture' => 'انتخاب تکسچر',
        'SelectSceneCategory' => 'انتخاب نوع کاربری',

        'CompareActivate' => 'فعال‌سازی مقایسه',
        'Download' => 'دانلود',
        'DownloadBlocked' => 'جلوگیری از دانلود',

        'UploadSuccess' => 'آپلود تصویر',
        'UploadFailed' => 'خطا در آپلود تصویر',

        'LoginSuccess' => 'ورود موفق',
        'LoginFailed' => 'ورود ناموفق',
        'Logout' => 'خروج از حساب',
    ],

    'texture_types' => [
        'FLOOR' => 'کف',
        'WALL' => 'دیوار',
    ],
];
