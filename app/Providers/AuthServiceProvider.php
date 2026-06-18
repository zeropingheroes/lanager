<?php

namespace Zeropingheroes\Lanager\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Zeropingheroes\Lanager\Policies\DiscordChannelWebhookMessagePolicy;
use Zeropingheroes\Lanager\Policies\DiscordChannelWebhookPolicy;
use Zeropingheroes\Lanager\Policies\ImagePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::resource('images', ImagePolicy::class);

        Gate::resource('discord-channel-webhooks', DiscordChannelWebhookPolicy::class, [
            'index' => 'index',
            'create' => 'create',
            'delete' => 'delete',
        ]);

        Gate::resource('discord-webhook-messages', DiscordChannelWebhookMessagePolicy::class, [
            'create' => 'create',
            'store' => 'store',
        ]);
    }
}
