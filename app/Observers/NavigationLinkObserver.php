<?php

namespace Zeropingheroes\Lanager\Observers;

use Illuminate\Support\Facades\Cache;
use Zeropingheroes\Lanager\NavigationLink;

class NavigationLinkObserver
{
    /**
     * Listen to the NavigationLink saving event.
     *
     * @param  NavigationLink $navigationLink
     * @return void
     */
    public function saving(NavigationLink $navigationLink)
    {
        // Remove the site URL from links so that all on-site links are relative links
        $navigationLink->url = str_replace(config('app.url'), '', $navigationLink->url);
    }

    /**
     * Listen to the NavigationLink saved event.
     *
     * @param  NavigationLink $navigationLink
     * @return void
     */
    public function saved(NavigationLink $navigationLink)
    {
        // Clear the cache whenever a navigation link is created
        Cache::forget('navigationLinks');
    }
    /**
     * Listen to the NavigationLink deleted event.
     *
     * @param  NavigationLink $navigationLink
     * @return void
     */
    public function deleted(NavigationLink $navigationLink)
    {
        // Clear the cache whenever a navigation link is created
        Cache::forget('navigationLinks');
    }
}