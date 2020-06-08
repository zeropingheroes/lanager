<?php

namespace Zeropingheroes\Lanager\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Collection;
use Throwable;
use Zeropingheroes\Lanager\Services\UpdateSteamUserAppsService;

class UpdateOutdatedUserAppsAfterSuccessfulAuth
{

    /**
     * Handle the event.
     *
     * @param Login $login
     * @return void
     * @throws Throwable
     */
    public function handle(Login $login)
    {
        $steamMetadata = $login->user->SteamMetadata;

        if (!$steamMetadata) {
            return;
        }

        // If the user's apps have never been updated
        // or have not been updated in the last hour
        if ($steamMetadata->apps_updated_at == null || $steamMetadata->apps_updated_at < now()->subHour()) {

            // Update their apps
            $service = new UpdateSteamUserAppsService((new Collection($login->user)));
            $service->update();
        }
    }
}
