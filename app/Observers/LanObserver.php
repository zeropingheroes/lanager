<?php

namespace Zeropingheroes\Lanager\Observers;

use Illuminate\Support\Facades\Cache;
use Zeropingheroes\Lanager\Lan;

class LanObserver
{
    /**
     * Find and cache the current LAN
     */
    private function cacheCurrentLan()
    {

        // Clear the previously cached current LAN (if present)
        Cache::forget('currentLan');

        // If there's a LAN happening now
        // get it and cache it until the end of the LAN
        $lan = Lan::happeningNow()->first();
        if ($lan) {
            Cache::put('currentLan', $lan, new \DateTime($lan->end));
        } else {
            // Otherwise, cache the most recent past event forever,
            // which is safe to do, as when a LAN is created, edited
            // or deleted, this cache item will be invalidated.
            // Note: if there is no LAN, null will be cached
            Cache::forever(
                'currentLan',
                Lan::past()
                    ->orderBy('end', 'desc')
                    ->first()
            );
        }
    }

    /**
     * Listen to the Lan saved event.
     *
     * @param  Lan $lan
     * @return void
     */
    public function saved(Lan $lan)
    {
        $this->cacheCurrentLan();
    }

    /**
     * Listen to the Lan deleted event.
     *
     * @param  Lan $lan
     * @return void
     */
    public function deleted(Lan $lan)
    {
        $this->cacheCurrentLan();
    }
}