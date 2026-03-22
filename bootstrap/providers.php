<?php

use GrahamCampbell\Markdown\MarkdownServiceProvider;
use Zeropingheroes\Lanager\Providers\AppServiceProvider;
use Zeropingheroes\Lanager\Providers\AuthServiceProvider;
use Zeropingheroes\Lanager\Providers\ViewComposerServiceProvider;

return [
    AppServiceProvider::class,
    AuthServiceProvider::class,
    ViewComposerServiceProvider::class,
    MarkdownServiceProvider::class,
];
