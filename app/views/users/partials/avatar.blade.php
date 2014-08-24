<?php
	$state = $user->states()->latest()->first();

	// default to small size
	$size = empty($size) ? 'small' : $size;
	switch($size)
	{
		case 'small':	$src = $user->avatar;
			break;
		case 'medium':	$src = $user->getMediumAvatarUrl();
			break;
		case 'large':	$src = $user->getLargeAvatarUrl();
			break;
		default: 		$src = $user->avatar;
	}

	if( isset($classes) && ! is_array($classes) ) $classes = array($classes);
	$classes[] = 'avatar';
	$classes[] = 'avatar-'.$size;
		
	// if we have a useable state for this user
	// set the image classes and title based on
	// what they are doing
	if( count($state) )
	{
		if( isset($state->application_id) )
		{
			$classes[] = 'avatar-in-game';
			$title = 'In-Game: '.$state->application->name;
		}
		elseif( $state->status )
		{
			$classes[] = 'avatar-online';
			$title = $state->getStatus();
		}
		else
		{
			$classes[] = 'avatar-offline';
			$title = $state->getStatus();
		}
	}
	else
	{
		$classes[] = 'avatar-offline';
		$title = 'Status unknown';
	}

?>
<img class="{{{ implode(' ', $classes) }}}" src="{{ $src }}" alt="Avatar for {{{ $user->username }}}" title="{{{ $title }}}">
