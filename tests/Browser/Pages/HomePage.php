<?php

declare(strict_types=1);

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
    #[\Override]
    public function assert(Browser $browser): void
    {
        //
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function elements(): array
    {
        return [
            '@element' => '#selector',
        ];
    }
}
