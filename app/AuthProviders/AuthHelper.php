<?php

namespace Zeropingheroes\Lanager\AuthProviders;

use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class AuthHelper
{
    public static $supportedProviders = [
        'steam' => Steam::class,
        'discord' => Discord::class,
    ];

    public static function provider($name): AuthProviderInterface
    {
        if (!array_key_exists($name, static::$supportedProviders)) {
            $message = __('phrase.provider-not-supported', ['provider' => $name]);
            Log::error($message);
            throw new InvalidArgumentException($message);
        }

        return new static::$supportedProviders[$name]();
    }
}