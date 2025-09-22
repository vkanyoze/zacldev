<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mailtrap Configuration for 2FA
    |--------------------------------------------------------------------------
    |
    | This configuration file contains settings for using Mailtrap
    | for sending 2FA verification codes in development/testing.
    |
    */

    'enabled' => env('MAILTRAP_ENABLED', true),
    
    'smtp' => [
        'host' => env('MAIL_HOST', 'sandbox.smtp.mailtrap.io'),
        'port' => env('MAIL_PORT', 587),
        'username' => env('MAIL_USERNAME'),
        'password' => env('MAIL_PASSWORD'),
        'encryption' => env('MAIL_ENCRYPTION', 'tls'),
    ],
    
    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'noreply@zacl.co.zm'),
        'name' => env('MAIL_FROM_NAME', 'ZACL Payment System'),
    ],
    
    '2fa' => [
        'enabled' => env('2FA_ENABLED', false),
        'code_length' => 6,
        'expiry_minutes' => 10,
        'max_attempts' => 3,
    ],
    
    'testing' => [
        'enabled' => env('MAILTRAP_TESTING', true),
        'inbox_url' => 'https://mailtrap.io/inboxes',
    ],
];
