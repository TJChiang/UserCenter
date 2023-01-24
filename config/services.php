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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'line' => [
        'channel_id' => env('LINE_CHANNEL_ID'),
        'secret' => env('LINE_SECRET'),
        'authorize_endpoint' => 'https://access.line.me/oauth2/v2.1/authorize',
        'token_endpoint' => 'https://api.line.me/oauth2/v2.1/token',
        'get_user_profile_url' => 'https://api.line.me/v2/profile',
        'verify_token_url' => 'https://api.line.me/oauth2/v2.1/verify',
        'picture_url' => 'https://profile.line-scdn.net',
        'revoke_url' => 'https://api.line.me/oauth2/v2.1/revoke',
        'redirect' => 'https://localhost/line/callback'
    ],
];
