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

Event::subscribe('Zeropingheroes\Lanager\Handlers\PlaylistItemVoteHandler');
Event::subscribe('Zeropingheroes\Lanager\Handlers\UserHandler');

Event::listen('lanager.*', function()
{
	Log::info( Event::firing() ); 
});
