<?php

namespace Zeropingheroes\Lanager\Listeners;

use Illuminate\Auth\Events\Login;
use Zeropingheroes\Lanager\Models\Attendee;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\User;

class UpdateLanAttendeesTable
{
    /**
     * Handle the event.
     */
    public function handle(Login $login): void
    {
        if (! $login->user instanceof User) {
            return;
        }

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
