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

    'dotapi' => [
        'disabled' => env('DOTAPI_DISABLED', false),
        'xuid_disabled' => env('DOTAPI_XUID_DISABLED', false),
        'key' => env('DOTAPI_KEY'),
        'domain' => env('DOTAPI_DOMAIN', 'https://grunt.api.dotapi.gg'),
        'version' => env('DOTAPI_VERSION', '2023-06-24'),
        'cooldown' => (int) env('DOTAPI_COOLDOWN', 120),
        'competitive' => [
            'key' => env('DOTAPI_CURRENT_SEASON_KEY', '13-1'),
            'season' => (int) env('DOTAPI_CURRENT_SEASON', 13),
        ],
        'warning_message' => env('DOTAPI_WARNING_MESSAGE'),
    ],

    'faceit' => [
        'key' => env('FACEIT_KEY'),
        'domain' => env('FACEIT_DOMAIN', 'https://open.faceit.com'),
        'webhook' => [
            'secret' => env('FACEIT_WEBHOOK_SECRET', ''),
        ],
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

    'sentry' => [
        'crons' => [
            'pull-metdata' => env('SENTRY_CRON_PULL_METADATA', 'e84010bc-19d7-4586-85b8-9c12855a2329'),
            'refresh-analytics' => env('SENTRY_CRON_REFRESH_ANALYTICS', '0c5b14b2-9929-45ec-b661-69ce66341e9d'),
        ],
    ],

    'halo' => [
        'playlists' => [
            'bot-bootcamp' => env('HALO_PLAYLISTS_BOT_BOOTCAMP', 'a446725e-b281-414c-a21e-31b8700e95a1'),
            'survive-the-dead' => env('HALO_PLAYLISTS_SURVIVE_THE_DEAD', '3089c3e1-dafa-42a0-98ed-f29948f344a7'),
            'firefight-koth' => env('HALO_PLAYLISTS_FF_KOTH', '96aedf55-1c7e-46d5-bdaf-19a1329fb95d'),
            'firefight-heroic' => env('HALO_PLAYLISTS_FF_HEROIC', 'd8ac67e8-647c-4602-8af0-f42012ba8dd8'),
            'firefight-legendary' => env('HALO_PLAYLISTS_FF_LEGENDARY', '759021fe-1d82-470f-a2e6-e431300b384b'),
            'firefight-grunt-koth' => env('HALO_PLAYLISTS_FF_GRUNT_KOTH', 'e3a9b265-5883-4248-afde-37abafc122ab'),
            'firefight-grunt-heroic' => env('HALO_PLAYLISTS_FF_GRUNT_HEROIC', '78b10320-8bc4-491b-a3d5-80fa1ca847f8'),
            'firefight-grunt-legendary' => env('HALO_PLAYLISTS_FF_GRUNT_LEGENDARY', '014f22c9-1ed1-4cfd-9e3a-c1b744d78d8c'),
            'firefight-composer-normal' => env('HALO_PLAYLISTS_FF_COMPOSER_NORMAL', '1f58f2e2-324d-4770-bca7-e36c63662626'),
            'firefight-composer-heroic' => env('HALO_PLAYLISTS_FF_COMPOSER_HEROIC', '4ec3a07b-edec-4fdd-aa7e-668dc6398ac2'),
            'firefight-composer-legendary' => env('HALO_PLAYLISTS_FF_COMPOSER_LEGENDARY', 'd3c44874-7f46-4f8b-b9a8-92db24cf807e'),
            'firefight-battle-for-reach' => env('HALO_PLAYLISTS_FF_BATTLE_FOR_REACH', '05a31b26-514b-49ca-856c-3f2cb965a636'),
            'firefight-3person' => env('HALO_PLAYLISTS_FF_3P', 'a92aa2ae-26f1-441e-a0ba-3dedcc6269ce'),
            'firefight-fiesta' => env('HALO_PLAYLISTS_FF_FIESTA', '57f4f0c0-bce9-4a34-b1b0-6188ed0f0198'),
            'firefight-classic' => env('HALO_PLAYLISTS_FF_CLASSIC', '85bd3b5e-614b-4da1-823c-78be72840a96'),
        ],
        'botfarmer_threshold' => env('HALO_BOTFARMER_THRESHOLD', .50),
    ],

];
