<?php

namespace Zeropingheroes\Lanager\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Str;

class CreateApiTokenIfMissing
{

    /**
     * Handle the event.
     *
     * @param Login $login
     * @return void
     * @internal param object $event
     */
    public function handle(Login $login)
    {
        $user = $login->user;
        if(! $user->api_token) {
            $user->api_token = Str::random(60);
            $user->save();
        }
    }
}
