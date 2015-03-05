<?php

if( Authority::can('create', $resource) )
{
	// Set defaults for any options not specified
	$options = [
		'url'  => URL::route($resource.'.create'),
		'text' => ( ( ! isset($text) ) ? '' : $text ),
		'icon' => ( ( ! isset($icon) ) ? 'file'	  : $icon ),
		'size' => ( ( ! isset($size) ) ? 'small' : $size ),
		'type' => ( ( ! isset($type) ) ? 'normal' : $type ),
		'hover' => ( ( ! isset($hover) ) ? 'Create a new item' : $hover ),
	];

	echo View::make('buttons.url', $options);
}

