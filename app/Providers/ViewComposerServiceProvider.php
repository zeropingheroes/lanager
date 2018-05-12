<?php

namespace Zeropingheroes\Lanager\Providers;

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
            $view->with('errorCount', Log::where('read',0)->where('level', '>=', 250)->count()); // Notice and above
        });
        View::composer('layouts.partials.nav.primary', function ($view) {
            $view->with('navigationLinks',  NavigationLink::whereNull('parent_id')->with('children')->get());
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