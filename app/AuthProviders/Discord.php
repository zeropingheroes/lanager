<?php

namespace Zeropingheroes\Lanager\AuthProviders;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Contracts\User;
use Laravel\Socialite\Facades\Socialite;
use Zeropingheroes\Lanager\UserOAuthAccount;

class Discord implements AuthProviderInterface
{
    public static $name = 'Discord';

    public function getName(): string
    {
        return static::$name;
    }

    public function to(): RedirectResponse
    {
        return Socialite::with('discord')->redirect();
    }

    public function from(): User
    {
        $OAuthUser = Socialite::with('discord')->user();

        // Try the logged in user first, then try to find a User who has the Discord ID.
        $user = Auth::user();
        if (!$user ) {
            $user = UserOAuthAccount::where(['provider_id' => $OAuthUser->id, 'provider' => 'discord'])->firstOrFail()->user;
        }

        $user->accounts()->updateOrCreate(
            [
                'provider_id' => $OAuthUser->getId(),
                'provider' => 'discord',
            ],
            [
                'provider_id' => $OAuthUser->getId(),
                'provider' => 'discord',
                'username' => $OAuthUser->getNickname(),
                'avatar' => $OAuthUser->getAvatar(),
                'access_token' => $OAuthUser->token,
                'refresh_token' => $OAuthUser->refreshToken,
                'token_expiry' => Carbon::create()->add(CarbonInterval::fromString('+' . $OAuthUser->expiresIn . 's')),
            ]
        );

        return $OAuthUser;
    }

}