<?php

namespace Zeropingheroes\Lanager\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::morphMap(
            [
                'steam' => 'Zeropingheroes\Lanager\Models\SteamApp',
            ]
        );

        Paginator::useBootstrap();
    }
}
