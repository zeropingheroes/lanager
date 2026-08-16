<?php

namespace Tests\Browser\Pages\Events;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class EventDiscordNotificationMessageCreate extends Page
{
    /**
     * {@inheritDoc}
     */
    public function url(): string
    {
        return '/lans/*/events/*/discord-notification-message/create';
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function assert(Browser $browser): void
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function elements(): array
    {
        return [];
    }
}
