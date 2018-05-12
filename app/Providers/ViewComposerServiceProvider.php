<?php

namespace Zeropingheroes\Lanager\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Zeropingheroes\Lanager\Log;
use Zeropingheroes\Lanager\NavigationLink;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('layouts.partials.nav.admin', function ($view) {
            // Count of unread errors of level "notice" and above
            $view->with('errorCount', Log::where('read',0)->where('level', '>=', 250)->count()); // Notice and above
        });

        View::composer('layouts.partials.nav.primary', function ($view) {
            // Cached collection of top-level navigation links, and their children
            $navigationLinks = Cache::rememberForever('navigationLinks', function () {
                return NavigationLink::whereNull('parent_id')->with('children')->orderBy('position')->get();
            });
            $view->with('navigationLinks', $navigationLinks);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}