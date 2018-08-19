<?php

namespace Zeropingheroes\Lanager\Services;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;
use Syntax\SteamApi\Facades\SteamApi as Steam;
use Zeropingheroes\Lanager\UserOAuthAccount;

class UpdateSteamUserAppsService
{
    /**
     * LANager users to be updated
     *
     * @var Collection
     */
    protected $users = [];

    /**
     * Users whose apps were successfully updated
     *
     * @var array
     */
    protected $updated = [];

    /**
     * Users whose apps were not updated due to errors
     *
     * @var array
     */
    protected $failed = [];

    /**
     * Errors
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * @param Collection $users
     * @throws Exception
     * @internal param array $users
     */
    public function __construct(Collection $users)
    {
        if ($users->isEmpty()) {
            throw new Exception(__('phrase.one-or-more-users-must-be-provided'));
        }

        $this->users = $users;
        $this->errors = new MessageBag();
    }

    /**
     * @return MessageBag
     */
    public function errors(): MessageBag
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function getUpdated(): array
    {
        return $this->updated;
    }

    /**
     * @return array
     */
    public function getFailed(): array
    {
        return $this->failed;
    }

    /**
     * Update Steam users apps
     * @return void
     * @throws \Throwable
     */
    public function update(): void
    {
        $steamAccounts = UserOAuthAccount::where('provider', 'steam')
            ->whereIn('user_id', $this->users->pluck('id'))->get();

        // Update games for each user in turn
        foreach ($steamAccounts as $steamAccount) {
            try {
                $apps = Steam::player($steamAccount->provider_id)->GetOwnedGames();

                $appsVisible = (count($apps) != 0);

                $steamAccount->user->SteamMetadata()->updateOrCreate(
                    [],
                    [
                        'apps_visible' => $appsVisible,
                        'apps_updated_at' => now()
                    ]
                );

                foreach ($apps as $app) {
                    $steamAccount->user->SteamApps()
                        ->updateOrCreate(
                            ['steam_app_id' => $app->appId],
                            [
                                'playtime_two_weeks' => $app->playtimeTwoWeeks,
                                'playtime_forever' => $app->playtimeForever
                            ]
                        );
                }

                // Add the user to the updated array
                $this->updated[$steamAccount->provider_id] = $steamAccount->user->username;

            } catch (Exception $e) {
                $this->errors->add(
                    $steamAccount->provider_id,
                    __(
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