<?php

// Check the current user is allowed to edit this particular item (pass the Model object as $item)
if (Authority::can('edit', $resource, $item)) {
    // If an array of route parameters is specified use it, otherwise default to the singular resource id
    $parameters = (isset($parameters) && is_array($parameters)) ? $parameters : $item->id;

    // Set defaults for any options not specified
    $options = [
        'url' => URL::route($resource.'.edit', $parameters),
        'text' => ((!isset($text)) ? '' : $text),
        'icon' => ((!isset($icon)) ? 'pencil' : $icon),
        'size' => ((!isset($size)) ? 'small' : $size),
        'type' => ((!isset($type)) ? 'normal' : $type),
        'hover' => ((!isset($hover)) ? 'Edit this '.trans('resources.'.$resource) : $hover),
    ];

    echo View::make('buttons.url', $options);
}