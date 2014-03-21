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
	'Zeropingheroes\Lanager\Interfaces\SteamUserRepositoryInterface',
	'Zeropingheroes\Lanager\Repositories\LocomotiveSteamUserRepository'
	);

App::bind(
	'Zeropingheroes\Lanager\Interfaces\SteamAppRepositoryInterface',
	'Zeropingheroes\Lanager\Repositories\LocomotiveSteamAppRepository'
	);

App::bind(
	'Zeropingheroes\Lanager\Interfaces\StateRepositoryInterface',
	'Zeropingheroes\Lanager\Repositories\EloquentStateRepository'
	);