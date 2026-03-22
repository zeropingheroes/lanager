<?php

namespace Zeropingheroes\Lanager\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Str;

class CreateApiTokenIfMissing
{
    /**
     * Handle the event.
     */
    public function handle(Login $login): void
    {
        $user = $login->user;
        if (! $user->api_token) {
            $user->api_token = Str::random(60);
            $user->save();
        }
    }
}
