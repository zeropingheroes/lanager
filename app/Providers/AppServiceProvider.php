<?php

namespace Zeropingheroes\Lanager\Providers;

use Barryvdh\Debugbar\Facade as DebugbarFacade;
use Barryvdh\Debugbar\ServiceProvider as DebugbarServiceProvider;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Zeropingheroes\Lanager\Models\NavigationLink;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Observers\NavigationLinkObserver;
use Zeropingheroes\Lanager\Observers\UserObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Load debugbar if:
        // - env set to local
        // - debug enabled
        // - class exists
        if (
            $this->app->environment('local')
            && config('app.debug') == true
            && class_exists(DebugbarServiceProvider::class)
        ) {
            $this->app->register(DebugbarServiceProvider::class);
            $this->app->alias('Debugbar', DebugbarFacade::class);
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        NavigationLink::observe(NavigationLinkObserver::class);

        Relation::morphMap(
            [
                'steam' => 'Zeropingheroes\Lanager\Models\SteamApp',
            ]
        );

        Paginator::useBootstrap();
    }
}
