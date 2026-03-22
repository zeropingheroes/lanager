<?php

namespace Zeropingheroes\Lanager\Listeners;

use Illuminate\Auth\Events\Login;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Services\UpdateSteamUserAppsService;

class UpdateOutdatedUserAppsAfterSuccessfulAuth
{
    /**
     * Handle the event.
     */
    public function handle(Login $login): void
    {
        if (! $login->user instanceof User) {
            return;
        }
        $steamMetadata = $login->user->SteamMetadata;

        if (! $steamMetadata) {
            return;
        }

        // If the user's apps have never been updated
        // or have not been updated in the last hour
        if ($steamMetadata->apps_updated_at == null || $steamMetadata->apps_updated_at < now()->subHour()) {
            // Update their apps
            $service = new UpdateSteamUserAppsService(collect([$login->user]));
            $service->update();
        }
    }
}
