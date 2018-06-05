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

        User::observe(UserObserver::class);
        NavigationLink::observe(NavigationLinkObserver::class);
    }

    /**
     * Register any application services.
     * @return void
     * @throws Exception
     */
    public function register()
    {
        $command = array_get(request()->server(), 'argv.1');

        // Check required environment variables are set
        // unless the config has been cached, or the package:discover command is being run
        if (!$this->app->configurationIsCached() && $command != 'package:discover') {
            if (! env('STEAM_API_KEY')) {
                throw new Exception('STEAM_API_KEY not set in .env file');
            }
            if (! ctype_xdigit(env('STEAM_API_KEY')) || strlen(env('STEAM_API_KEY')) != 32) {
                throw new Exception('Invalid STEAM_API_KEY set in .env file');
            }
        }
    }
}
