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
    'Zeropingheroes\Lanager\Domain\Users\SteamUsers\SteamUserContract',
    'Zeropingheroes\Lanager\Domain\Users\SteamUsers\LocomotiveSteamUserRepository'
);

App::bind(
    'Zeropingheroes\Lanager\Domain\Applications\SteamApplications\SteamApplicationContract',
    'Zeropingheroes\Lanager\Domain\Applications\SteamApplications\LocomotiveSteamApplicationRepository'
);