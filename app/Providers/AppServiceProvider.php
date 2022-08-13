<?php

namespace Zeropingheroes\Lanager\Providers;

use Illuminate\Support\Arr;
use Barryvdh\Debugbar\Facade as DebugbarFacade;
use Barryvdh\Debugbar\ServiceProvider as DebugbarServiceProvider;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Exception;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use League\CommonMark\Inline\Element\Image;
use League\CommonMark\Inline\Element\Link;
use Zeropingheroes\Lanager\MarkdownRenderers\ExternalLinkRenderer;
use Zeropingheroes\Lanager\MarkdownRenderers\ResponsiveImageRenderer;
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
     * @throws Exception
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

        $command = Arr::get(request()->server(), 'argv.1');

        // Check required environment variables are set, unless:
        // - the config has been cached
        // - a setup command is being run
        if (
            !$this->app->configurationIsCached() && !in_array(
                $command,
                [
                    'package:discover',
                    'cache:clear',
                    'config:clear',
                    'view:clear',
                    'ide-helper:generate',
                    'ide-helper:meta',

                ]
            )
        ) {
            if (!env('STEAM_API_KEY')) {
                throw new Exception('STEAM_API_KEY not set in .env file');
            }
            if (!ctype_xdigit(env('STEAM_API_KEY')) || strlen(env('STEAM_API_KEY')) != 32) {
                throw new Exception('Invalid STEAM_API_KEY set in .env file');
            }
            if (!env('GOOGLE_API_KEY')) {
                throw new Exception('GOOGLE_API_KEY not set in .env file');
            }
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
        app('markdown.environment')->addInlineRenderer(
            Link::class,
            new ExternalLinkRenderer(request()->getHost())
        );
        // Make images responsive using Bootstrap 4 class
        app('markdown.environment')->addInlineRenderer(
            Image::class,
            new ResponsiveImageRenderer(['img-fluid'])
        );

        Relation::morphMap(
            [
                'steam' => 'Zeropingheroes\Lanager\SteamApp',
            ]
        );
    }
}
