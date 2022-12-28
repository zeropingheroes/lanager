<?php

namespace Tests\Browser\Pages\Achievements;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class AchievementEdit extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/achievements/*/edit';
    }

    /**
     * Assert that the browser is on the page.
     *
     * @param Browser $browser
     * @return void
     */
    public function assert(Browser $browser)
    {
        $browser->assertPathIs($this->url());
    }

    /**
     * Get the element shortcuts for the page.
     *
     * @return array
     */
    public function elements()
    {
        return [
            '@submit' => 'button[type=submit]',
        ];
    }
}
