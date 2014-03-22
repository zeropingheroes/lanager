<?php

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/

Artisan::add(new Zeropingheroes\Lanager\Commands\Install);
Artisan::resolve('Zeropingheroes\Lanager\Commands\SteamImportApps');
Artisan::resolve('Zeropingheroes\Lanager\Commands\SteamImportUserStates');