<?php

namespace Zeropingheroes\Lanager\AuthProviders;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Contracts\User;
use Laravel\Socialite\Facades\Socialite;
use Zeropingheroes\Lanager\Services\UpdateSteamUsersService;
use Zeropingheroes\Lanager\UserOAuthAccount;

class Steam implements AuthProviderInterface
{
    public static $name = 'Steam';

    public function getName(): string
    {
        return static::$name;
    }

    public function to(): RedirectResponse
    {
        return Socialite::with('steam')->redirect();
    }

    public function from(): User
    {
        $OAuthUser = Socialite::with('steam')->user();

        $service = new UpdateSteamUsersService($OAuthUser->id);
        $service->update();

        return $OAuthUser;
    }

}