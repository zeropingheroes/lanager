<?php

declare(strict_types=1);

namespace Tests\Browser\Pages\Achievements;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class AchievementEdit extends Page
{
    /**
     * {@inheritDoc}
     */
    public function url(): string
    {
        return '/achievements/*/edit';
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
