<?php

namespace Tests\Browser\Pages\Guides;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class GuideShow extends Page
{
    /**
     * {@inheritDoc}
     */
    public function url(): string
    {
        return '/lans/*/guides/*';
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
