<?php

namespace Zeropingheroes\Lanager\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Str;
use Zeropingheroes\Lanager\Models\User;

class CreateApiTokenIfMissing
{
    /**
     * Handle the event.
     */
    public function handle(Login $login): void
    {
        if (! $login->user instanceof User) {
            return;
        }

        $user = $login->user;
        if (! $user->api_token) {
            $user->api_token = Str::random(60);
            $user->save();
        }
    }
}
