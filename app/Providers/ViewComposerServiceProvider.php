<?php

namespace Zeropingheroes\Lanager\Providers;

use Cache;
use Illuminate\Support\ServiceProvider;
use View;
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

        View::composer(
            'layouts.partials.nav.primary',
            function ($view) {
                // Cached collection of top-level navigation links, and their children
                $navigationLinks = Cache::rememberForever(
                    'navigationLinks',
                    function () {
                        return NavigationLink::whereNull('parent_id')->orderBy('position')->get();
                    }
                );
                $view->with('navigationLinks', $navigationLinks);
            }
        );
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
