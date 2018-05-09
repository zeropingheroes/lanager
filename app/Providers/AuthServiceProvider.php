<?php

namespace Zeropingheroes\Lanager\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Zeropingheroes\Lanager\RoleAssignment;
use Zeropingheroes\Lanager\Policies\RoleAssignmentPolicy;
use Zeropingheroes\Lanager\Log;
use Zeropingheroes\Lanager\Policies\LogPolicy;
use Zeropingheroes\Lanager\Page;
use Zeropingheroes\Lanager\Policies\PagePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [

        // Remember to import the classes as no error will be thrown!
        RoleAssignment::class   => RoleAssignmentPolicy::class,
        Log::class              => LogPolicy::class,
        Page::class             => PagePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
