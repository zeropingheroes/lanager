<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;

class HomePage extends Page
{
    /**
     * {@inheritDoc}
     */
    public function url(): string
    {
        return '/';
    }

    /**
     * {@inheritDoc}
     */
    public function assert(Browser $browser): void
    {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function elements(): array
    {
        return [
            '@element' => '#selector',
        ];
    }
}
