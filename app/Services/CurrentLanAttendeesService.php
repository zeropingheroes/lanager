<?php

namespace Zeropingheroes\Lanager\Services;

use Zeropingheroes\Lanager\Lan;

class CurrentLanAttendeesService
{
    public function get()
    {
        // If there are no LANs, return all users
        if(Lan::count() == 0) {
            return User::all();
        }

        // If there's a LAN happening now, get it
        $lan = Lan::happeningNow()->first();

        // If there's not a LAN happening now
        if (! $lan) {
            // get the most recent past LAN
            $lan = Lan::past()->first();
        }

        // If there's no most recent past LAN
        if (! $lan) {
            // return all users
            return User::all();
        }

        // If there is a LAN, return all of the LAN's attendees
        return $lan->users()->get();
    }
}