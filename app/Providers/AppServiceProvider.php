<?php

namespace Zeropingheroes\Lanager\Providers;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\User;
use Zeropingheroes\Lanager\Observers\UserObserver;
use Zeropingheroes\Lanager\NavigationLink;
use Zeropingheroes\Lanager\Observers\NavigationLinkObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     * @return void
     * @throws Exception
     */
    public function boot()
    {
        if (!$this->app->configurationIsCached()) {
            if (! env('STEAM_API_KEY')) {
                throw new Exception('STEAM_API_KEY not set in .env file');
            }
            if (! ctype_xdigit(env('STEAM_API_KEY')) || strlen(env('STEAM_API_KEY')) != 32) {
                throw new Exception('Invalid STEAM_API_KEY set in .env file');
            }
        }

        User::observe(UserObserver::class);
        NavigationLink::observe(NavigationLinkObserver::class);

        // TODO: find better place for this

        // If there's a LAN happening now
        // get it and cache it until the end of the LAN
        $lan = Lan::happeningNow()->first();
        if ($lan) {
            Cache::put('currentLan', $lan, new \DateTime($lan->end));
        } else {
            // Otherwise, cache the most recent past event forever,
            // which is safe to do, as when a LAN is created, edited
            // or deleted, this cache item will be invalidated
            Cache::forever('currentLan', Lan::past()->first());
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
