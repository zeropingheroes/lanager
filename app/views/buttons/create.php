<?php

// Check the current user is allowed to create a resource of this type
if ( Authority::can('create', $resource) )
{
	// If an array of route parameters is specified use it, otherwise set to null
	$parameters = ( isset($parameters) && is_array($parameters) ) ? $parameters : null;

	// Set defaults for any options not specified
	$options = [
		'url'  => URL::route($resource.'.create', $parameters),
		'text' => ( ( ! isset($text) ) ? '' : $text ),
		'icon' => ( ( ! isset($icon) ) ? 'file'	  : $icon ),
		'size' => ( ( ! isset($size) ) ? 'small' : $size ),
		'type' => ( ( ! isset($type) ) ? 'normal' : $type ),
		'hover' => ( ( ! isset($hover) ) ? 'Create a new '.trans('resources.'.$resource) : $hover ),
	];

	echo View::make('buttons.url', $options);
}