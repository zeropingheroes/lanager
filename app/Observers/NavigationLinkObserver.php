<?php

namespace Zeropingheroes\Lanager\Observers;

use Illuminate\Support\Facades\Cache;
use Zeropingheroes\Lanager\NavigationLink;

class NavigationLinkObserver
{
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