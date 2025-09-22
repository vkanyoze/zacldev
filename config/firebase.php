<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Firebase services including Authentication,
    | Firestore, and other Firebase features.
    |
    */

    'project_id' => env('FIREBASE_PROJECT_ID'),
    'api_key' => env('FIREBASE_API_KEY'),
    'auth_domain' => env('FIREBASE_AUTH_DOMAIN'),
    'storage_bucket' => env('FIREBASE_STORAGE_BUCKET'),
    'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID'),
    'app_id' => env('FIREBASE_APP_ID'),

    /*
    |--------------------------------------------------------------------------
    | Firebase Service Account
    |--------------------------------------------------------------------------
    |
    | Path to the Firebase service account JSON file.
    | This is used for server-side operations.
    |
    */

    'service_account' => [
        'path' => env('FIREBASE_SERVICE_ACCOUNT_PATH', storage_path('app/firebase-service-account.json')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase Auth Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for Firebase Authentication features.
    |
    */

    'auth' => [
        'enabled' => env('FIREBASE_AUTH_ENABLED', true),
        'webhook_secret' => env('FIREBASE_WEBHOOK_SECRET'),
        'auto_create_users' => env('FIREBASE_AUTO_CREATE_USERS', true),
        'sync_user_data' => env('FIREBASE_SYNC_USER_DATA', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Providers
    |--------------------------------------------------------------------------
    |
    | Configure which social providers are enabled.
    |
    */

    'social_providers' => [
        'google' => env('FIREBASE_GOOGLE_ENABLED', true),
        'facebook' => env('FIREBASE_FACEBOOK_ENABLED', true),
        'twitter' => env('FIREBASE_TWITTER_ENABLED', false),
        'github' => env('FIREBASE_GITHUB_ENABLED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase Database Rules
    |--------------------------------------------------------------------------
    |
    | Default security rules for Firebase Realtime Database.
    |
    */

    'database_rules' => [
        'users' => [
            'read' => 'auth != null',
            'write' => 'auth != null && auth.uid == $uid'
        ]
    ],
];
