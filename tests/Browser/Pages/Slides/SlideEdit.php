<?php

declare(strict_types=1);

namespace Tests\Browser\Pages\Slides;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class SlideEdit extends Page
{
    /**
     * {@inheritDoc}
     */
    public function url(): string
    {
        return '/lans/*/slides/*/edit';
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
