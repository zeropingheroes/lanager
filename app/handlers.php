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

Event::subscribe('Zeropingheroes\Lanager\Playlists\Items\Votes\VoteHandler');
Event::subscribe('Zeropingheroes\Lanager\Users\UserHandler');

Event::listen('lanager.*', function()
{
	Log::info( Event::firing() ); 
});
