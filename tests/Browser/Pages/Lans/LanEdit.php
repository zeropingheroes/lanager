<?php

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
