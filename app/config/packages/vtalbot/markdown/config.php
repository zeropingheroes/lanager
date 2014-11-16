<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Markdown Storage Paths
    |--------------------------------------------------------------------------
    | Based on $app['path'] value.
    */

    'paths' => array('/markdown'),

    /*
    |--------------------------------------------------------------------------
    | Markdown Routes Paths
    |--------------------------------------------------------------------------
    */

    'routes' => array(
        '',
    ),

    /*
    |--------------------------------------------------------------------------
    | Markdown Routes extensions
    |--------------------------------------------------------------------------
    */

    'extensions' => array('markdown', 'md'),

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

    'options' => array(
        'empty_element_suffix' => ' />',
        'tab_width' => 4,
        'no_markup' => true,
        'no_entities' => true,
        'predef_urls' => array(),
        'predef_titles' => array(),

        // Use PHP Markdown with extra.
        'use_extra' => true,

        'fn_id_prefix' => '',
        'fn_link_title' => '',
        'fn_backlink_title' => '',
        'fn_link_class' => 'footnote-ref',
        'fn_backlink_class' => 'footnote-backref',
        'code_class_prefix' => '',
        'code_attr_on_pre' => false,
        'predef_abbr' => array(),
    ),

);
