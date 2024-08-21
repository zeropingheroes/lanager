<?php

namespace Zeropingheroes\Lanager\Services;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;
use Syntax\SteamApi\Facades\SteamApi;
use Throwable;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;

class UpdateSteamUserAppsService
{
    /**
     * LANager users to be updated.
     */
    protected Collection $users;

    /**
     * Users whose apps were successfully updated.
     */
    protected array $updated = [];

    /**
     * Users whose apps were not updated due to errors.
     */
    protected array $failed = [];

    /**
     * Errors.
     */
    protected MessageBag $errors;

    /**
     * Construct the class
     * @throws Exception
     */
    public function __construct(Collection $users)
    {
        if ($users->isEmpty()) {
            throw new Exception(trans('phrase.one-or-more-users-must-be-provided'));
        }

        $this->users = $users;
        $this->errors = new MessageBag();
    }


    /**
     * Get the errors
     */
    public function errors(): MessageBag
    {
        return $this->errors;
    }


    /**
     * Get the users who were updated
     */
    public function getUpdated(): array
    {
        return $this->updated;
    }


    /**
     * Get the users who were not updated
     */
    public function getFailed(): array
    {
        return $this->failed;
    }

    /**
     * Update Steam users apps.
     * @throws Throwable
     */
    public function update(): void
    {
        $steamAccounts = UserOAuthAccount::where('provider', 'steam')
            ->whereIn('user_id', $this->users->pluck('id'))->get();

        // Update games for each user in turn
        foreach ($steamAccounts as $steamAccount) {
            try {
                $apps = SteamApi::player($steamAccount->provider_id)->GetOwnedGames();

                $appsVisible = (count($apps) != 0);

                $steamAccount->user->steamMetadata()->updateOrCreate(
                    [],
                    [
                        'apps_visible' => $appsVisible,
                        'apps_updated_at' => now(),
                    ]
                );

                foreach ($apps as $app) {
                    $steamAccount->user->steamApps()
                        ->updateOrCreate(
                            ['steam_app_id' => $app->appId],
                            [
                                'playtime_two_weeks' => $app->playtimeTwoWeeks,
                                'playtime_forever' => $app->playtimeForever,
                            ]
                        );
                }

                // Add the user to the updated array
                $this->updated[$steamAccount->provider_id] = $steamAccount->user->username;
            } catch (Exception $e) {
                $this->errors->add(
                    $steamAccount->provider_id,
                    trans(
                        'phrase.unable-to-update-data-for-user-x',
                        ['x' => $steamAccount->user->username, 'error' => $e->getMessage()]
                    )
                );
                // Add the user to the failed array
                $this->failed[$steamAccount->provider_id] = $steamAccount->user->username;
            }
        }
    }
}
