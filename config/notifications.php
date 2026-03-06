<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SMS Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your SMS provider settings here. The 'driver' option specifies
    | which SMS driver to use. Available drivers: 'log', 'http'
    |
    | For the 'http' driver, you can configure a generic HTTP API endpoint
    | that accepts POST requests with 'to' and 'message' fields.
    |
    */

    'sms' => [
        'driver' => env('SMS_DRIVER', 'log'),

        'from' => env('SMS_FROM', 'WorshipRealm'),

        // Jusibe Driver Configuration
        'jusibe' => [
            'public_key' => env('JUSIBE_PUBLIC_KEY'),
            'access_token' => env('JUSIBE_ACCESS_TOKEN'),
            'sender_id' => env('JUSIBE_SENDER_ID', 'WorshipRealm'),
        ],

        // HTTP Driver Configuration
        'http' => [
            'url' => env('SMS_API_URL'),
            'method' => env('SMS_API_METHOD', 'POST'),
            'headers' => [
                'Authorization' => 'Bearer ' . env('SMS_API_KEY'),
                'Content-Type' => 'application/json',
            ],
            // Map your API's expected field names
            'fields' => [
                'to' => env('SMS_FIELD_TO', 'to'),
                'message' => env('SMS_FIELD_MESSAGE', 'message'),
                'from' => env('SMS_FIELD_FROM', 'from'),
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Email Notifications
    |--------------------------------------------------------------------------
    |
    | Enable or disable email notifications. When enabled, members with email
    | addresses will receive email notifications in addition to SMS.
    |
    */

    'email' => [
        'enabled' => env('NOTIFICATIONS_EMAIL_ENABLED', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Types
    |--------------------------------------------------------------------------
    |
    | Enable or disable specific notification types.
    |
    */

    'types' => [
        'welcome_first_timer' => env('NOTIFY_WELCOME_FIRST_TIMER', false),
        'check_in_confirmation' => env('NOTIFY_CHECK_IN', false),
        'birthday_wish' => env('NOTIFY_BIRTHDAY', false),
    ],
];
