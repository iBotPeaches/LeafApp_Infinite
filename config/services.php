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
        'key' => env('AUTOCODE_KEY'),
        'domain' => env('AUTOCODE_DOMAIN', 'https://halo.api.stdlib.com'),
        'version' => env('AUTOCODE_VERSION', '0.1.2'),
        'cooldown' => env('AUTOCODE_COOLDOWN', 120),
    ],

    'xboxapi' => [
        'domain' => env('XBOXAPI_DOMAIN', 'https://xbl-api.prouser123.me'),
    ],

];
