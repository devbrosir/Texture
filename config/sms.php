<?php

declare(strict_types=1);

use Modules\Sms\Drivers\SmsIrDriver;

return [
    'default' => env('SMS_DEFAULT_DRIVER', 'smsir'),

    'drivers' => [
        'smsir' => [
            'base_url' => env('SMSIR_BASE_URL', 'https://api.sms.ir/v1/'),
            'driver' => SmsIrDriver::class,
            'api_key' => env('SMSIR_API_KEY'),
            'default_number' => env('SMSIR_NUMBER'),
        ],
    ],

];
