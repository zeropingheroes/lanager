<?php

namespace Zeropingheroes\Lanager\Providers;

use Barryvdh\Debugbar\Facade as DebugbarFacade;
use Barryvdh\Debugbar\ServiceProvider as DebugbarServiceProvider;
use Exception;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use League\CommonMark\Inline\Element\Image;
use League\CommonMark\Inline\Element\Link;
use Zeropingheroes\Lanager\MarkdownRenderers\ExternalLinkRenderer;
use Zeropingheroes\Lanager\MarkdownRenderers\ResponsiveImageRenderer;
use Zeropingheroes\Lanager\Role;
use Zeropingheroes\Lanager\SteamUserStatusCode;
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
                'steam_app' => 'Zeropingheroes\Lanager\SteamApp',
            ]
        );
        if (!$this->app->configurationIsCached() && !in_array($this->getCommand(), ['package:discover', 'db:seed'])) {
            if (!$this->systemTablesPopulated()) {
                throw new Exception('Database empty - please run php artisan db:seed');
            }
        }
    }

    /**
     * Register any application services.
     * @return void
     * @throws Exception
     */
    public function register()
    {
        if ($this->app->environment('local')) {
            $this->app->register(DebugbarServiceProvider::class);
            $this->app->alias('Debugbar', DebugbarFacade::class);
        }

        // Check required environment variables are set unless the config has been cached, or the package:discover command is being run
        if (!$this->app->configurationIsCached() && $this->getCommand() != 'package:discover') {
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
     * Check if tables required for the app to function are populated
     * @return bool
     */
    private function systemTablesPopulated(): bool
    {
        $models = [
            SteamUserStatusCode::class,
            Role::class,
        ];

        foreach ($models as $model) {
            if (!$model::count()) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the currently executing command
     * @return string
     */
    private function getCommand()
    {
        return array_get(request()->server(), 'argv.1');
    }
}
