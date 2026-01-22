<?php

declare(strict_types=1);

return [

    // Field(s) used for login
    'login_fields' => ['email', 'mobile'],

    // OTP Settings
    'otp' => [
        'length' => 6,
        'expire' => 300, // seconds
        'retry_after' => 60, // retry send after seconds
    ],

    // Token settings
    'token' => [
        'driver' => 'sanctum',  // sanctum | jwt | none
        'ttl' => 3600,
    ],

    // Guards
    'guards' => ['api', 'web'],
];
