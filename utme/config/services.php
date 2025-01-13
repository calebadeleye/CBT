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
        'secret_key' => 'FLWSECK-ff77cecdff5358c64403fb809c631315-X',
        'public_key' => 'FLWPUBK-e2172628c845f52aa5270a20de8fb95b-X ',
        'bank_lists_url' => 'https://api.flutterwave.com/v3/banks/NG',
        'bank_resolve_account_number' => 'https://api.flutterwave.com/v3/accounts/resolve',
        'verify_payment' => 'https://api.flutterwave.com/v3/transactions/',
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

];
