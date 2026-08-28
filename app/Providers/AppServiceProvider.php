<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Zeropingheroes\Lanager\Models\SteamApp;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::morphMap(
            [
                'steam' => SteamApp::class,
            ]
        );

        Paginator::useBootstrap();
    }
}
