<?php

// Check the current user is allowed to update this particular item (pass the Model object)
if( Authority::can('update', $resource, $item) )
{
	// If an array of parameters is specified use it, otherwise default to the singular resource id
	$parameters = ( isset($parameters) && is_array($parameters) ) ? $parameters : $item->id;

	// Set defaults for any options not specified
	$options = [
		'url'		=> URL::route($resource.'.update', $parameters),
		'method'	=> 'PUT',
		'text'		=> ( ( ! isset($text) )		? ''			: $text ),
		'icon'		=> ( ( ! isset($icon) )		? 'floppySave'	: $icon ),
		'size'		=> ( ( ! isset($size) )		? 'small'		: $size ),
		'type'		=> ( ( ! isset($type) )		? 'normal'		: $type ),
		'hover'		=> ( ( ! isset($hover) )	? ''			: $hover ),
		'class'		=> ( ( ! isset($class) )	? 'inline'		: $class ),
		'id'		=> ( ( ! isset($id) )		? ''			: $id ),
	];

	echo View::make('buttons.form', $options);
}