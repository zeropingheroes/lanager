<?php

declare(strict_types=1);

namespace Tests\Browser\Pages\Lans;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class LanEdit extends Page
{
    /**
     * {@inheritDoc}
     */
    public function url(): string
    {
        return '/lans/*/edit';
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
