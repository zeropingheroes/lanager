<?php

// Check the current user is allowed to store this particular item (pass the Model object)
if( Authority::can('store', $resource) )
{
	// If an array of parameters is specified use it, otherwise default to the singular resource id
	$parameters = ( isset($parameters) && is_array($parameters) ) ? $parameters : $item->id;

	$resourceName = trans('resources.'.str_singular($resource));

	// Set defaults for any options not specified
	$text  = ( ! isset($text)  ) ? '' : $text;
	$icon  = ( ! isset($icon)  ) ? 'trash'  : $icon;
	$size  = ( ! isset($size)  ) ? 'small' : $size;
	$type  = ( ! isset($type)  ) ? 'normal' : $type;
	$hover = ( ! isset($hover) ) ? 'Delete this '.$resourceName : $hover;

	$class = ( ! isset($class) ) ? 'inline' : $class;

	// Echo the form
	echo Form::inline(
						[
							'url' 			=> URL::route($resource.'.store', $parameters), 
							'method' 		=> 'POST',
							'class'			=> $class,
						]
					);

	// Set any hidden input data
	if( isset($data) && is_array($data) )
	{
		foreach($data as $name => $value)
		{
			echo Form::hidden( $name, $value );
		}
	}

	// Create the basic button
	$button = Button::{$type}($text);

	// If an icon is specified add it
	if( ! empty($icon) )	$button = $button->prependIcon(Icon::{$icon}());

	// If hover text is specified add it
	if( ! empty($hover) )	$button = $button->addAttributes(['title' => $hover]);

	// If a size other than normal is specified set it
	if( $size != 'normal' )	$button = $button->{$size}();

	// Echo out finished button
	echo $button->submit();

	// Close the form
	echo Form::close();

}
