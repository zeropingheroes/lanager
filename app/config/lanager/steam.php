<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Steam Web API Key
	|--------------------------------------------------------------------------
	|
	| To use the LANager you will need to obtain a Steam Web API key
	| Get one here:
	| http://steamcommunity.com/dev/apikey
	|
	|
	*/

	'apikey' => '',

	/*
	|--------------------------------------------------------------------------
	| Polling Interval
	|--------------------------------------------------------------------------
	| 
	| The number of seconds you have scheduled in the SteamImportUserStates
	| command. Used to generate queries with correct timing information
	|
	| NOTE: This does not set how often the LANager polls Steam! That can only
	| be changed by editing your cron job.
	| 
	| If you are not polling Steam every minute please set this to your custom
	| polling interval
	|
	*/

	'pollingInterval' => 60,

];
