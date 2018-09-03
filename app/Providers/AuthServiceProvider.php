<?php

namespace Zeropingheroes\Lanager\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Zeropingheroes\Lanager\Achievement;
use Zeropingheroes\Lanager\Policies\AchievementPolicy;
use Zeropingheroes\Lanager\EventSignup;
use Zeropingheroes\Lanager\Policies\EventSignupPolicy;
use Zeropingheroes\Lanager\Policies\ImagePolicy;
use Zeropingheroes\Lanager\RoleAssignment;
use Zeropingheroes\Lanager\Policies\RoleAssignmentPolicy;
use Zeropingheroes\Lanager\Log;
use Zeropingheroes\Lanager\Policies\LogPolicy;
use Zeropingheroes\Lanager\Guide;
use Zeropingheroes\Lanager\Policies\GuidePolicy;
use Zeropingheroes\Lanager\NavigationLink;
use Zeropingheroes\Lanager\Policies\NavigationLinkPolicy;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\Policies\LanPolicy;
use Zeropingheroes\Lanager\EventType;
use Zeropingheroes\Lanager\Policies\EventTypePolicy;
use Zeropingheroes\Lanager\Event;
use Zeropingheroes\Lanager\Policies\EventPolicy;
use Zeropingheroes\Lanager\User;
use Zeropingheroes\Lanager\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [

        // Remember to import the classes, as no error will be thrown if you don't!
        RoleAssignment::class   => RoleAssignmentPolicy::class,
        Log::class              => LogPolicy::class,
        Guide::class            => GuidePolicy::class,
        NavigationLink::class   => NavigationLinkPolicy::class,
        Lan::class              => LanPolicy::class,
        EventType::class        => EventTypePolicy::class,
        Event::class            => EventPolicy::class,
        User::class             => UserPolicy::class,
        EventSignup::class      => EventSignupPolicy::class,
        Achievement::class      => AchievementPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::resource('images', ImagePolicy::class);
    }
}
