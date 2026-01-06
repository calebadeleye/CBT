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

    'flutterwave' => [
        'secret_key' => env('FLW_SECRET_KEY'),
        'public_key' => env('FLW_PUBLIC_KEY'),
        'bank_lists_url' => 'https://api.flutterwave.com/v3/banks/NG',
        'bank_resolve_account_number' => 'https://api.flutterwave.com/v3/accounts/resolve',
        'verify_payment' => 'https://api.flutterwave.com/v3/transactions/',
        'transfer_url' => 'https://api.flutterwave.com/v3/transfers',
    ],

    'paystack' => [
        'secret_key' => env('PAYSTACK_SECRET_KEY'),
        'public_key' => env('PAYSTACK_PUBLIC_KEY'),
    ],

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

    'turnstile' => [
        'site_key' => env('CLOUDFLARE_TURNSTILE_SITE_KEY'),
        'secret_key' => env('CLOUDFLARE_TURNSTILE_SECRET_KEY'),
    ],

];
