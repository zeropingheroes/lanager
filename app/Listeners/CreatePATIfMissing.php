<?php

namespace Zeropingheroes\Lanager\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreatePATIfMissing
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Login $login)
    {
        $user = $login->user;
        if($user->tokens()->where('name', 'Default')->where('revoked', 0)->count() == 0)
            $user->createToken('Default')->accessToken;
    }
}
