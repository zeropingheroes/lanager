<?php

namespace Tests\Browser\Pages\Venues;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class VenueCreate extends Page
{
    /**
     * @inheritDoc
     */
    public function url(): string
    {
        return '/venues/create';
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
            '@submit' => 'button[type=submit]',
        ];
    }
}
