<?php

namespace Tests\Browser\Pages\NavigationLinks;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class NavigationLinkEdit extends Page
{
    /**
     * Get the URL for the page.
     *
     * @return string
     */
    public function url()
    {
        return '/navigation-links/*/edit';
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
