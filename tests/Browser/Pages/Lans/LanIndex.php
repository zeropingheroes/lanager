<?php

namespace Tests\Browser\Pages\Lans;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class LanIndex extends Page
{
    /**
     * {@inheritDoc}
     */
    public function url(): string
    {
        return '/lans';
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
            '@create' => 'a#create-lan-button',
        ];
    }
}
