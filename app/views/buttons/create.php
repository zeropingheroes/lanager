<?php

if( Authority::can('create', $resource) )
{
	$parameters = ( isset($parameters) && is_array($parameters) ) ? $parameters : null;

	// Set defaults for any options not specified
	$options = [
		'url'  => URL::route($resource.'.create', $parameters),
		'text' => ( ( ! isset($text) ) ? '' : $text ),
		'icon' => ( ( ! isset($icon) ) ? 'file'	  : $icon ),
		'size' => ( ( ! isset($size) ) ? 'small' : $size ),
		'type' => ( ( ! isset($type) ) ? 'normal' : $type ),
		'hover' => ( ( ! isset($hover) ) ? 'Create a new '.trans('resources.'.str_singular($resource)) : $hover ),
	];

	echo View::make('buttons.url', $options);
}

