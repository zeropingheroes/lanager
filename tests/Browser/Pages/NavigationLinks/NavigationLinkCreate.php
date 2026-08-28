<?php

declare(strict_types=1);

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
