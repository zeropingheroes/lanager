<?php

namespace Zeropingheroes\Lanager\Providers;

use Exception;
use Illuminate\Support\ServiceProvider;
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
