<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Markdown Storage Paths
    |--------------------------------------------------------------------------
    | Based on $app['path'] value.
    */

    'paths' => ['/markdown'],

    /*
    |--------------------------------------------------------------------------
    | Markdown Routes Paths
    |--------------------------------------------------------------------------
    */

    'routes' => [
        '',
    ],

    /*
    |--------------------------------------------------------------------------
    | Markdown Routes extensions
    |--------------------------------------------------------------------------
    */

    //'extensions' => ['markdown', 'md'],

    /*
    |--------------------------------------------------------------------------
    | Add routes for files ending with defined extensions from above.
    |--------------------------------------------------------------------------
    */

    'add_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | PHP Markdown Options
    |--------------------------------------------------------------------------
    */

    'options' => [
        'empty_element_suffix' => ' />',
        'tab_width' => 4,
        'no_markup' => true,
        'no_entities' => true,
        'predef_urls' => [],
        'predef_titles' => [],

        // Use PHP Markdown with extra.
        'use_extra' => true,

        'fn_id_prefix' => '',
        'fn_link_title' => '',
        'fn_backlink_title' => '',
        'fn_link_class' => 'footnote-ref',
        'fn_backlink_class' => 'footnote-backref',
        'code_class_prefix' => '',
        'code_attr_on_pre' => false,
        'predef_abbr' => [],
    ],

);
