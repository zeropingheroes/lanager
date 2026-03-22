<?php

namespace Tests\Browser\Pages\Events;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class EventShow extends Page
{
    /**
     * {@inheritDoc}
     */
    public function url(): string
    {
        return '/lans/*/events/*';
    }

    /**
     * {@inheritDoc}
     */
    public function assert(Browser $browser): void
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * {@inheritDoc}
     */
    public function elements(): array
    {
        return [
        ];
    }
}
