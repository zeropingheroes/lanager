<?php

namespace Zeropingheroes\Lanager\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Zeropingheroes\Lanager\Policies\RoleAssignmentPolicy;
use Zeropingheroes\Lanager\RoleAssignment;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        RoleAssignment::class => RoleAssignmentPolicy::class,
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
