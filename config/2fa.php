<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Two-Factor Authentication Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration options for the 2FA system.
    | You can easily enable/disable 2FA for development and production.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Enable 2FA
    |--------------------------------------------------------------------------
    |
    | Set to true to enable 2FA, false to disable.
    | When disabled, users will go directly to dashboard after login.
    |
    */
    'enabled' => env('2FA_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | 2FA Code Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for 2FA codes.
    |
    */
    'code' => [
        'length' => 6,
        'expiry_minutes' => 10,
        'max_attempts' => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Settings
    |--------------------------------------------------------------------------
    |
    | How long 2FA verification remains valid.
    |
    */
    'session' => [
        'validity_hours' => 24,
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Settings
    |--------------------------------------------------------------------------
    |
    | Email configuration for 2FA codes.
    |
    */
    'email' => [
        'from_address' => env('MAIL_FROM_ADDRESS', 'noreply@zacl.co.zm'),
        'from_name' => env('MAIL_FROM_NAME', 'ZACL Payment System'),
    ],
];
