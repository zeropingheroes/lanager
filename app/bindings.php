<?php

/*
|--------------------------------------------------------------------------
| Application Bindings
|--------------------------------------------------------------------------
|
| Here is where you can choose a repository implementation for
| a given repository interface.
|
*/

App::bind(
	'Zeropingheroes\Lanager\Users\SteamUsers\SteamUserContract',
	'Zeropingheroes\Lanager\Users\SteamUsers\LocomotiveSteamUserRepository'
	);

App::bind(
	'Zeropingheroes\Lanager\Applications\SteamApplications\SteamApplicationContract',
	'Zeropingheroes\Lanager\Applications\SteamApplications\LocomotiveSteamApplicationRepository'
	);