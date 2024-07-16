<?php

namespace Tests\Browser\Pages\LanAttendees;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class LanAttendeeIndex extends Page
{
    /**
     * @inheritDoc
     */
    public function url(): string
    {
        return '/lans/*/attendees';
    }
    /**
     * @inheritDoc
     */
    public function assert(Browser $browser): void
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * @inheritDoc
     */
    public function elements(): array
    {
        return [
        ];
    }
}
