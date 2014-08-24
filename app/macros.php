<?php
/*
|--------------------------------------------------------------------------
| Button - Create Resource
|--------------------------------------------------------------------------
|
| Checks if user has permission to create a resource of the specified type
| and returns a button to do so
|
*/
HTML::macro('resourceCreate', function($resourceName, $buttonValue)
{
	if( Authority::can('create', $resourceName) ) return Button::link(URL::route($resourceName.'.create'), $buttonValue);
});

/*
|--------------------------------------------------------------------------
| Button - Edit Resource
|--------------------------------------------------------------------------
|
| Checks if user has permission to edit the sepcified resource item and 
| returns a button to do so
|
*/
HTML::macro('resourceUpdate', function($resourceName, $resourceItem, $buttonValue)
{
	$resourceItemId = (is_object($resourceItem) ? $resourceItem->id : $resourceItem);
	if( Authority::can('update', $resourceName, $resourceItem) ) return Button::link(URL::route($resourceName.'.edit', array($resourceName => $resourceItemId)), $buttonValue);
});


/*
|--------------------------------------------------------------------------
| Button - Delete Resource
|--------------------------------------------------------------------------
|
| Checks if user has permission to edit a sepcified resource item and 
| returns a button to do so
|
*/
HTML::macro('resourceDelete', function($resourceName, $resourceItem, $buttonValue, $icon = '')
{
	$resourceItemId = (is_object($resourceItem) ? $resourceItem->id : $resourceItem);
	if( Authority::can('delete', $resourceName, $resourceItem) )
	{
		$output = Form::open(array('route' => array($resourceName.'.destroy', $resourceItemId), 'method' => 'DELETE', 'data-confirm' => 'Are you sure?', 'class' => 'resource-destroy'));
		if( !empty($icon) )
		{
			$output .= Button::submit($buttonValue, array('title' => 'Delete '.$resourceName))->with_icon($icon);
		}
		else
		{
			$output .= Button::submit($buttonValue, array('title' => 'Delete '.$resourceName));
		}
		$output .= Form::close();

		return $output;
	}
});

/*
|--------------------------------------------------------------------------
| Date Time
|--------------------------------------------------------------------------
|
| Create bootstrap-compatible date picker with optional linked 2nd picker
|
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