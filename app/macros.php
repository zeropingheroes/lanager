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
| User Avatar
|--------------------------------------------------------------------------
|
| Display a user's avatar with status info and coloured border a la Steam
|
*/
HTML::macro('userAvatar', function($user, $size = 'small', $classes = array())
{
	if( ! is_array($classes) ) $classes = array($classes);
	$classes[] = 'avatar';
	$classes[] = 'avatar-'.$size;
	
	$state = $user->states()->latest()->first();
	
	if( count($state) )
	{
		if( isset($state->application_id) )
		{
			$classes[] = 'in-game';
			$title = 'In-Game: '.e($state->application->name);
		}
		elseif( $state->status )
		{
			$classes[] = 'online';
			$title = $state->getStatus();
		}
		else
		{
			$classes[] = 'offline';
			$title = $state->getStatus();
		}
	}
	else
	{
		$classes[] = 'offline';
		$title = 'Status unknown';
	}

	switch($size)
	{
		case 'small':
			$src = $user->avatar;
			break;
		case 'medium':
			$src = $user->getMediumAvatarUrl();
			break;
		case 'large':
			$src = $user->getLargeAvatarUrl();
			break;
		default: $src = $user->avatar;
	}
	$classes = implode(' ', $classes);
	return '<img class="'.$classes.'" src="'.$src.'" alt="Avatar" title="'.$title.'">';
});

/*
|--------------------------------------------------------------------------
| Date Picker
|--------------------------------------------------------------------------
|
| Create bootstrap-compatible date picker with optional linked 2nd picker
|
*/
HTML::macro('datePicker', function($name, $options = array())
{
	$output = '
		<script type="text/javascript">
			$(function () {
				$("#'.$name.'").datetimepicker({
					language: "en-gb"
				});
			';

		if(isset($options['linkedPickerName']))
		{
			$output .= '
				$("#'.$name.'").on("change.dp",function (e) {
					if(!!e.date) {
						$("#'.$options['linkedPickerName'].'").data("DateTimePicker").setStartDate(moment(e.date).subtract("days", 1));
						$("#'.$options['linkedPickerName'].'").data("DateTimePicker").setDate(moment(e.date));
					}
				});
				$("#'.$options['linkedPickerName'].'").on("change.dp",function (e) {
					$("#'.$name.'").data("DateTimePicker").setEndDate(e.date);
				});
			';
		}

	$output .= '
		});
	</script>
	';

	return $output;
});