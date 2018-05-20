<?php

namespace Zeropingheroes\Lanager\Listeners;

use Illuminate\Auth\Events\Login;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\LanAttendee;

class UpdateLanAttendeesTable
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Login $login
     * @return void
     * @internal param object $event
     */
    public function handle(Login $login)
    {
        $currentLan = Lan::where('start', '<', now())
            ->where('end', '>', now())->first();

        if ($currentLan->count()) {
            LanAttendee::firstOrCreate(
                [
                    'user_id' => $login->user->id,
                    'lan_id' => $currentLan->id,
                ]
            )->touch();
        }
    }
}
