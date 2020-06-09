<?php

namespace Zeropingheroes\Lanager\Providers;

use Arr;
use Barryvdh\Debugbar\Facade as DebugbarFacade;
use Barryvdh\Debugbar\ServiceProvider as DebugbarServiceProvider;
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
     * @throws Exception
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(DebugbarServiceProvider::class);
            $this->app->alias('Debugbar', DebugbarFacade::class);
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $command = Arr::get(request()->server(), 'argv.1');

        // Check required environment variables are set unless the config has been cached, or the package:discover command is being run
        if (! $this->app->configurationIsCached() && $command != 'package:discover') {
            if (! env('STEAM_API_KEY')) {
                throw new Exception('STEAM_API_KEY not set in .env file');
            }
            if (! ctype_xdigit(env('STEAM_API_KEY')) || strlen(env('STEAM_API_KEY')) != 32) {
                throw new Exception('Invalid STEAM_API_KEY set in .env file');
            }
            if (! env('GOOGLE_API_KEY')) {
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
