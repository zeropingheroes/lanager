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
HTML::macro('button', function($route, $item = NULL)
{
	// Extract resource information
	$action		= substr( $route, strrpos( $route, '.' )+1 );	// e.g. destroy
	$resource	= str_replace('.' . $action, '', $route);		// e.g. shout
	$item		= is_numeric($item) ? $item : NULL;				// e.g. 35
	
	// Build URL
	$url		= URL::route($route, [$resource => $item]);

	// Exit if the user does not have permission to perform the action on the item
	if( Authority::cannot($action, $resource, $item) ) return;

	// Generate the button for the given action
	if( $action == 'create' )	return Button::normal(ucfirst($action))->prependIcon(Icon::file())->asLinkTo($url);
	if( $action == 'edit' )		return Button::normal(ucfirst($action))->prependIcon(Icon::pencil())->asLinkTo($url);
	if( $action == 'destroy' )
	{
		$confirmation = trans( 'confirmation.before.resource.destroy', ['resource' => str_singular($resource)]);
		
		return	Form::inline(['url' => $url, 'method' => 'DELETE', 'data-confirm' => $confirmation])
			 .	Button::normal(ucfirst($action))->prependIcon(Icon::trash())->submit()
			 .	Form::close();
	}
});

/**
 * Generate HTML/JS date and time picker
 *
 * @param  string			$name
 * @return string
 */
Form::macro('dateTimePicker', function($name)
{
	$input = Form::text($name, NULL, array('placeholder' => 'YYYY-MM-DD HH:MM'));
	$js = '<script type="text/javascript">
			$(function () {
				$("#'.$name.'").datetimepicker({
					sideBySide: true,
					format: "YYYY-MM-DD HH:mm",
				});
			});
		</script>';

	return $js.$input;
});