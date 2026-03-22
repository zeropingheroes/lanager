<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Page as BasePage;

abstract class Page extends BasePage
{
    /**
     * {@inheritDoc}
     */
    public static function siteElements(): array
    {
        return [
            '@element' => '#selector',
        ];
    }
}
