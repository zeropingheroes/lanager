<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Observers;

use Illuminate\Support\Facades\Cache;
use Zeropingheroes\Lanager\Models\NavigationLink;

class NavigationLinkObserver
{
    /**
     * Listen to the NavigationLink saving event.
     */
    public function saving(NavigationLink $navigationLink): void
    {
        // Remove the site URL from links so that all on-site links are relative links
        $navigationLink->url = str_replace(config('app.url'), '', $navigationLink->url);
    }

    /**
     * Listen to the NavigationLink saved event.
     */
    public function saved(NavigationLink $navigationLink): void
    {
        // Clear the cache whenever a navigation link is created
        Cache::forget('navigationLinks');
    }

    /**
     * Listen to the NavigationLink deleted event.
     */
    public function deleted(NavigationLink $navigationLink): void
    {
        // Clear the cache whenever a navigation link is created
        Cache::forget('navigationLinks');
    }
}
