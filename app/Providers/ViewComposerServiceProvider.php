<?php

namespace Zeropingheroes\Lanager\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Zeropingheroes\Lanager\Log;

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
            $view->with('errorCount', Log::where('level', '>', 200)->count());
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