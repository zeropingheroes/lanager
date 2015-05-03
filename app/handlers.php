<?php

/*
|--------------------------------------------------------------------------
| Event Handlers
|--------------------------------------------------------------------------
|
| Here is where you can register event handlers that subscribe to
| particular event types
|
*/

Event::subscribe('Zeropingheroes\Lanager\Domain\PlaylistItemVotes\PlaylistItemVoteHandler');
Event::subscribe('Zeropingheroes\Lanager\Domain\Users\UserHandler');

// Log all service actions
Event::listen('lanager.services.*', function( $parameters )
{
	$eventName = str_replace( 'lanager.services.', '', Event::firing() );

	$username = is_object( Auth::user() ) ? Auth::user()->username : 'guest';

	Log::debug( e($username) . ': ' . $eventName , $parameters );
});