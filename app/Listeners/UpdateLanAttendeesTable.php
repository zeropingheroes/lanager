<?php

namespace Zeropingheroes\Lanager\Listeners;

use Illuminate\Auth\Events\Login;
use Zeropingheroes\Lanager\Attendee;
use Zeropingheroes\Lanager\Lan;

class UpdateLanAttendeesTable
{
    /**
     * Handle the event.
     *
     * @param Login $login
     * @return void
     */
    public function handle(Login $login)
    {
        $lanHappeningNow = Lan::where('start', '<', now())
            ->where('end', '>', now())->first();

        if ($lanHappeningNow) {
            Attendee::firstOrCreate(
                [
                    'user_id' => $login->user->id,
                    'lan_id' => $lanHappeningNow->id,
                ]
            )->touch();
        }
    }
}
