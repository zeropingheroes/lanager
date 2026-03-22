<?php

namespace Tests\Browser\Pages\NavigationLinks;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class NavigationLinkCreate extends Page
{
    /**
     * {@inheritDoc}
     */
    public function url(): string
    {
        return '/navigation-links/create';
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
            '@submit' => 'button[type=submit]',
        ];
    }
}
