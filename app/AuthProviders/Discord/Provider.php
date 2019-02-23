<?php

namespace Zeropingheroes\Lanager\AuthProviders\Discord;

use SocialiteProviders\Discord\Provider as DiscordProvider;

class Provider extends DiscordProvider
{
    protected $scopes = [
        'identify',
        'email',
        'connections',
    ];
}