<?php
	// default to small size
	$size = empty($size) ? 'small' : $size;
	
	switch($size)
	{
		case 'small':	$url = $user->present()->avatarSmall;
			break;
		case 'medium':	$url = $user->present()->avatarMedium;
			break;
		case 'large':	$url = $user->present()->avatarLarge;
			break;
		default: 		$url = $user->present()->avatarSmall;
	}

	if( isset($classes) && ! is_array($classes) ) $classes = array($classes);
	$classes[] = 'avatar';
	$classes[] = 'avatar-'.$size;
		
	if( $user->state()->count() )
	{
		if( isset($user->state()->application_id) )
		{
			$classes[] = 'avatar-in-game';
			$title = 'In-Game: ' . $user->state()->application->name;
		}
		elseif( $user->state()->status )
		{
			$classes[] = 'avatar-online';
			$title = $user->state()->present()->statusText;
		}
		else
		{
			$classes[] = 'avatar-offline';
			$title = $user->state()->present()->statusText;
		}
	}
	else
	{
		$classes[] = 'avatar-offline';
		$title = 'Status unknown';
	}

?>
<img class="{{{ implode(' ', $classes) }}}" src="{{ $url }}" alt="Avatar for {{{ $user->username }}}" title="{{{ $title }}}">
