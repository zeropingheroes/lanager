<?php

namespace Tests\Browser\Pages\Achievements;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class AchievementEdit extends Page
{
    /**
     * @inheritDoc
     */
    public function url(): string
    {
        return '/achievements/*/edit';
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
