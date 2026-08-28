<?php

declare(strict_types=1);

namespace Tests\Browser\Pages\LanAttendees;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class LanAttendeeIndex extends Page
{
    /**
     * {@inheritDoc}
     */
    public function url(): string
    {
        return '/lans/*/attendees';
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
        ];
    }
}
