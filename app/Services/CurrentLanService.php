<?php

namespace Zeropingheroes\Lanager\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Zeropingheroes\Lanager\Lan;

class CurrentLanService
{
    /**
     * Get the current LAN
     * @return Lan
     */
    public function get()
    {
        // If there's a LAN cached, return it
        if (Cache::get('currentLan') instanceof Lan) {
            return Cache::get('currentLan');
        }

        // If there's a LAN happening now, cache it until it ends, and return it
        $lan = Lan::happeningNow()->first();
        if ($lan) {
            Cache::put('currentLan', $lan, new Carbon($lan->end));
            return $lan;
        }

        // If there isn't a LAN happening now, cache the most recent past LAN forever
        // which could be null, if there are no past LANs
        $lan = Lan::past()->orderBy('end', 'desc')->first();
        Cache::forever('currentLan', $lan);
        return $lan;
    }

    /**
     * Update the current LAN from the database, cache it, and return it
     * @return Lan
     */
    public function update()
    {
        Cache::forget('currentLan');
        return $this->get();
    }
}