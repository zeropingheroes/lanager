<?php

// Check the current user is allowed to destroy this particular item (pass the Model object)
if( Authority::can('destroy', $resource, $item) )
{
	// If an array of parameters is specified use it, otherwise default to the singular resource id
	$parameters = ( isset($parameters) && is_array($parameters) ) ? $parameters : $item->id;

	// Get resource name and confirmation message for use in hover/confirmation message alert
	$resourceName = trans('resources.'.$resource);
	$defaultConfirmation = trans('confirmation.before.resource.destroy', ['resource' => $resourceName]);

	// Set defaults for any options not specified
	$options = [
		'url'			=> URL::route($resource.'.destroy', $parameters),
		'method'		=> 'DELETE',
		'text'			=> ( ( ! isset($text) )			? ''							: $text ),
		'icon'			=> ( ( ! isset($icon) )			? 'trash'						: $icon ),
		'size'			=> ( ( ! isset($size) )			? 'small'						: $size ),
		'type'			=> ( ( ! isset($type) )			? 'normal'						: $type ),
		'hover'			=> ( ( ! isset($hover) )		? 'Delete this '.$resourceName	: $hover ),
		'class'			=> ( ( ! isset($class) )		? 'inline'						: $class ),
		'id'			=> ( ( ! isset($id) )			? ''							: $id ),
		'confirmation'	=> ( ( ! isset($confirmation) )	? $defaultConfirmation			: $confirmation ),
	];

	echo View::make('buttons.form', $options);

}
