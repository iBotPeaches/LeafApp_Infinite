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

    'autocode' => [
        'disabled' => env('AUTOCODE_DISABLED', false),
        'key' => env('AUTOCODE_KEY'),
        'domain' => env('AUTOCODE_DOMAIN', 'https://halo.api.stdlib.com'),
        'version' => env('AUTOCODE_VERSION', '1.5.0'),
        'cooldown' => env('AUTOCODE_COOLDOWN', 120),
        'competitive' => [
            'season' => env('AUTOCODE_CURRENT_SEASON', 2),
            'version' => env('AUTOCODE_CURRENT_SEASON_VERSION', 1),
        ],
        'warning_message' => env('AUTOCODE_WARNING_MESSAGE')
    ],

    'xboxapi' => [
        'domain' => env('XBOXAPI_DOMAIN', 'https://xbl-api.prouser123.me'),
        'enabled' => env('XBOXAPI_ENABLED', true),
    ],

    'faceit' => [
        'key' => env('FACEIT_KEY'),
        'domain' => env('FACEIT_DOMAIN', 'https://open.faceit.com'),
        'webhook' => [
            'secret' => env('FACEIT_WEBHOOK_SECRET', '')
        ]
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT'),
    ],

    'tinify' => [
        'key' => env('IMAGE_OPTIMIZE_KEY'),
        'domain' => env('IMAGE_DOMAIN', 'https://api.tinify.com'),
    ],

];
