<?php

declare(strict_types=1);

namespace Tests\Browser\Pages;

use Laravel\Dusk\Page as BasePage;

abstract class Page extends BasePage
{
    /**
     * {@inheritDoc}
     */
    #[\Override]
    public static function siteElements(): array
    {
        return [
            '@element' => '#selector',
        ];
    }
}
