<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    // 'mailgun' => [
    //     'domain' => env('MAILGUN_DOMAIN'),
    //     'secret' => env('MAILGUN_SECRET'),
    // ],

    // 'ses' => [
    //     'key' => env('SES_KEY'),
    //     'secret' => env('SES_SECRET'),
    //     'region' => 'us-east-1',
    // ],

    // 'sparkpost' => [
    //     'secret' => env('SPARKPOST_SECRET'),
    // ],

    // 'stripe' => [
    //     'model' => Zeropingheroes\Lanager\User::class,
    //     'key' => env('STRIPE_KEY'),
    //     'secret' => env('STRIPE_SECRET'),
    // ],

        'steam' => [
        'client_id' => null,
        'client_secret' => env('STEAM_API_KEY'),
        'redirect' => env('APP_URL').'/auth/steam/callback',
    ],

    'discord' => [
        'client_id' => env('DISCORD_KEY'),
        'client_secret' => env('DISCORD_SECRET'),
        'redirect' => env('APP_URL').'/auth/discord/callback',
        'token' => env('DISCORD_TOKEN'),
    ],

];
