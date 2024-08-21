<?php

namespace Tests\Browser\Pages\Slides;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class SlideEdit extends Page
{
    /**
     * @inheritDoc
     */
    public function url(): string
    {
        return '/lans/*/slides/*/edit';
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
