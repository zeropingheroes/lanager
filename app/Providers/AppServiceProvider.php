<?php

namespace Zeropingheroes\Lanager\Providers;

use Illuminate\Pagination\Paginator;
use Barryvdh\Debugbar\Facade as DebugbarFacade;
use Barryvdh\Debugbar\ServiceProvider as DebugbarServiceProvider;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Zeropingheroes\Lanager\NavigationLink;
use Zeropingheroes\Lanager\Observers\NavigationLinkObserver;
use Zeropingheroes\Lanager\Observers\UserObserver;
use Zeropingheroes\Lanager\User;

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

        // Add extended Markdown renderers
        // Open external links in a new window
//        app('markdown.environment')->addInlineRenderer(
//            Link::class,
//            new ExternalLinkRenderer(request()->getHost())
//        );
        // Make images responsive using Bootstrap 4 class
//        app('markdown.environment')->addInlineRenderer(
//            Image::class,
//            new ResponsiveImageRenderer(['img-fluid'])
//        );

        Relation::morphMap(
            [
                'steam' => 'Zeropingheroes\Lanager\SteamApp',
            ]
        );

        Paginator::useBootstrap();
    }
}
