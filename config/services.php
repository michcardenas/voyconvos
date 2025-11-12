<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    
  'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT'),
    ],

    'google_maps' => [
        'key' => env('GOOGLE_MAPS_API_KEY'),
    ],

    'mercadopago' => [
  'access_token' => env('MERCADO_PAGO_ACCESS_TOKEN', env('MERCADOPAGO_ACCESS_TOKEN')),
    'public_key' => env('MERCADO_PAGO_PUBLIC_KEY', env('MERCADOPAGO_PUBLIC_KEY')),
        ],

           'uala' => [
        'client_id' => env('UALA_CLIENT_ID'),
        'client_secret' => env('UALA_CLIENT_SECRET'),
        'username' => env('UALA_USERNAME'),
        'is_dev' => env('UALA_IS_DEV', true), // true para staging, false para producción
    ],

    'apple' => [
    'client_id' => env('APPLE_CLIENT_ID'),
    'team_id' => env('APPLE_TEAM_ID'),
    'key_id' => env('APPLE_KEY_ID'),
    'private_key' => env('APPLE_PRIVATE_KEY'),
    'redirect' => env('APPLE_REDIRECT_URI'),
],

];
