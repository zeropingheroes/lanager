<?php

namespace Zeropingheroes\Lanager\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Zeropingheroes\Lanager\Models\NavigationLink;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function boot(): void
    {
        View::composer(
            'layouts.partials.nav.primary',
            function ($view): void {
                // Cached collection of top-level navigation links, and their children
                $navigationLinks = Cache::rememberForever(
                    'navigationLinks',
                    fn () => NavigationLink::whereNull('parent_id')->orderBy('position')->get()
                );
                $view->with('navigationLinks', $navigationLinks);
            }
        );
    }
}
