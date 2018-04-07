<?php

namespace Zeropingheroes\Lanager\Providers;

use Illuminate\Support\ServiceProvider;
use Zeropingheroes\Lanager\Observers\UserObserver;
use Zeropingheroes\Lanager\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
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
