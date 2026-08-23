<?php

namespace Tests\Browser\Pages\EventDiscordNotificationMessages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class EventDiscordNotificationMessageEdit extends Page
{
    /**
     * {@inheritDoc}
     */
    public function url(): string
    {
        return '/lans/*/events/*/discord-notification-message/edit';
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
        return [
            '@submit' => 'button[type=submit]',
        ];
    }
}
