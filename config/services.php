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

    'steam' => [
        'client_id' => null,
        'client_secret' => env('STEAM_API_KEY'),
        'redirect' => rtrim(env('APP_URL'), '/') . '/auth/steam/callback',
        'allowed_hosts' => [parse_url(env('APP_URL'), PHP_URL_HOST)],
        'api_key' => env('STEAM_API_KEY'),
    ],

];
