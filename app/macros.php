<?php

/**
 * Generate HTML buttons for CRUD operations on resources
 * checking that the logged in user can perform the operation
 *
 * @param  string			$route
 * @param  integer|null		$item
 * @param  array			$options
 * @return string
 */
HTML::macro('button', function($route, $item = NULL, $options = [])
{
	// Extract resource information
	$action		= substr( $route, strrpos( $route, '.' )+1 );
	$resource	= str_replace('.' . $action, '', $route);
	$item		= is_numeric($item) ? $item : NULL;
	$url		= URL::route($route, [$resource => $item]);

	// Check if the user has permission to perform the action
	if( Authority::cannot($action, $resource, $item) ) return;

	// Set size and value options to sensible defaults
	$size		= ( isset($options['size']) ) ? $options['size'] : '';
	$value		= ( ! isset($options['value']) ) ? ucfirst($action) : $options['value'];
	
	// Create the button type to call call based on the action type
	$type		= ( $action == 'destroy' ) ? 'submit' : 'link';
	$button		= ( empty($size) ) ? $type : $size . '_' . $type;

	// Generate the button for the given action
	if( $action == 'create' )	return Button::$button($url, $value, ['title' => ucfirst($action) . ' a new ' . str_singular($resource)])->with_icon('file');
	if( $action == 'edit' )		return Button::$button($url, $value, ['title' => ucfirst($action) . ' this ' . str_singular($resource)])->with_icon('pencil');
	if( $action == 'destroy' )
	{
		$confirmation = ( ! isset($options['confirmation']) ) ? 'Are you sure you want to permanently delete this ' . str_singular($resource) . '?' : $options['confirmation'];
		
		return	Form::open(['url' => $url, 'method' => 'DELETE', 'data-confirm' => $confirmation, 'class' => 'resource-destroy'])
			 .	Button::$button($value, ['title' => ucfirst($action) . ' this ' . str_singular($resource)])->with_icon('trash')
			 .	Form::close();
	}
});

/**
 * Generate HTML/JS date and time picker
 *
 * @param  string			$name
 * @return string
 */
Form::macro('dateTime', function($name)
{
	$input = Form::text($name, NULL, array('placeholder' => 'YYYY-MM-DD HH:MM:SS'));
	$js = '<script type="text/javascript">
			$(function () {
				$("#'.$name.'").datetimepicker({
					format: "YYYY-MM-DD HH:mm:ss",
				});
			});
		</script>';

	return $js.$input;
});