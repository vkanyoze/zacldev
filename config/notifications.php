<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Notification Channel
    |--------------------------------------------------------------------------
    |
    | This option controls the default notification channel that will be used
    | when no specific channel is specified. This can be overridden on a
    | per-notification basis.
    |
    */
    'default' => env('NOTIFICATION_DEFAULT_CHANNEL', 'database'),

    /*
    |--------------------------------------------------------------------------
    | Notification Channels
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the notification channels that your
    | application supports. Out of the box, Laravel supports 'mail', 'database',
    | and 'broadcast' notification channels.
    |
    */
    'channels' => [
        'mail' => [
            'driver' => 'mail',
            'queue' => env('NOTIFICATION_MAIL_QUEUE', 'default'),
        ],
        'database' => [
            'driver' => 'database',
            'table' => 'notifications',
        ],
        'broadcast' => [
            'driver' => 'broadcast',
            'broadcaster' => env('BROADCAST_DRIVER', 'pusher'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Preferences
    |--------------------------------------------------------------------------
    |
    | These are the default notification preferences for users. These can be
    | overridden by individual users in their profile settings.
    |
    */
    'default_preferences' => [
        'email' => true,
        'database' => true,
        'browser' => true,
        'sound' => true,
        'digest' => [
            'enabled' => true,
            'frequency' => 'daily', // daily, weekly
            'time' => '18:00',
        ],
        'types' => [
            'system' => true,
            'user' => true,
            'payment' => true,
            'support' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Types
    |--------------------------------------------------------------------------
    |
    | Define the types of notifications that can be sent in the application.
    | Each type can have its own configuration for channels, templates, etc.
    |
    */
    'types' => [
        'system' => [
            'name' => 'System Notifications',
            'description' => 'Important system updates and maintenance notifications',
            'channels' => ['database', 'mail'],
            'required' => true,
        ],
        'user' => [
            'name' => 'User Activity',
            'description' => 'Notifications about user signups, profile updates, etc.',
            'channels' => ['database', 'mail'],
            'required' => false,
        ],
        'payment' => [
            'name' => 'Payment Notifications',
            'description' => 'Payment received, failed, or refunded notifications',
            'channels' => ['database', 'mail'],
            'required' => true,
        ],
        'support' => [
            'name' => 'Support Tickets',
            'description' => 'Updates on support tickets and customer service requests',
            'channels' => ['database', 'mail'],
            'required' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Icons
    |--------------------------------------------------------------------------
    |
    | Define the icons to be used for different notification types in the UI.
    |
    */
    'icons' => [
        'default' => 'bell',
        'types' => [
            'system' => 'cog',
            'user' => 'user',
            'payment' => 'credit-card',
            'support' => 'life-ring',
            'success' => 'check-circle',
            'error' => 'exclamation-circle',
            'warning' => 'exclamation-triangle',
            'info' => 'info-circle',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Expiration
    |--------------------------------------------------------------------------
    |
    | Define how long notifications should be kept before being automatically
    | deleted. Set to null to keep notifications indefinitely.
    |
    */
    'expire_days' => 30,

    /*
    |--------------------------------------------------------------------------
    | Notification Sound
    |--------------------------------------------------------------------------
    |
    | Configure the sound that plays when a new notification is received.
    |
    */
    'sound' => [
        'enabled' => true,
        'url' => '/sounds/notification.mp3',
        'volume' => 0.5,
    ],

    /*
    |--------------------------------------------------------------------------
    | Real-time Updates
    |--------------------------------------------------------------------------
    |
    | Configure real-time notification updates using Laravel Echo.
    |
    */
    'realtime' => [
        'enabled' => env('NOTIFICATION_REALTIME_ENABLED', true),
        'channel' => 'admin.notifications.{id}',
        'event' => 'App\Events\AdminNotificationEvent',
    ],
];
