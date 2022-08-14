<?php

namespace Zeropingheroes\Lanager\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        'SocialiteProviders\Manager\SocialiteWasCalled' => [
            'SocialiteProviders\Steam\SteamExtendSocialite@handle',
        ],
        'Illuminate\Auth\Events\Login' => [
            'Zeropingheroes\Lanager\Listeners\CreateApiTokenIfMissing',
            'Zeropingheroes\Lanager\Listeners\UpdateLanAttendeesTable',
            'Zeropingheroes\Lanager\Listeners\AwardLanAchievementToAttendee',
            'Zeropingheroes\Lanager\Listeners\UpdateOutdatedUserAppsAfterSuccessfulAuth',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
