<?php

namespace Zeropingheroes\Lanager\Services;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;
use Throwable;
use Zeropingheroes\Lanager\Models\SteamApp;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;
use Zeropingheroes\SteamApis\SteamWebApi\SteamWebApiConnector;

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
     *
     * @throws Exception
     */
    public function __construct(Collection $users)
    {
        if ($users->isEmpty()) {
            throw new Exception(trans('phrase.one-or-more-users-must-be-provided'));
        }

        $this->users = $users;
        $this->errors = new MessageBag;
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
     *
     * @throws Throwable
     */
    public function update(): void
    {
        $steamAccounts = UserOAuthAccount::where('provider', 'steam')
            ->whereIn('user_id', $this->users->pluck('id'))->get();

        $steamWebApi = app(SteamWebApiConnector::class);

        // Update games for each user in turn
        foreach ($steamAccounts as $steamAccount) {
            try {
                $ownedGames = $steamWebApi->GetOwnedGames(
                    steamid: $steamAccount->provider_id,
                    include_appinfo: true,
                    include_played_free_games: true
                );

                $appsVisible = (count($ownedGames) != 0);

                $steamAccount->user->steamMetadata()->updateOrCreate(
                    [],
                    [
                        'apps_visible' => $appsVisible,
                        'apps_updated_at' => now(),
                    ]
                );

                foreach ($ownedGames as $ownedGame) {
                    // LANager populates the apps table from Valve's IStoreService/GetAppList API
                    // which does not return apps that Valve have delisted from Steam.
                    // However, Valve's IPlayerService/GetOwnedGames API does return delisted apps
                    // owned by users, so when we encounter them, add them to the database.
                    if (! SteamApp::find($ownedGame->appid)) {
                        SteamApp::create(['id' => $ownedGame->appid, 'name' => $ownedGame->name]);
                    }
                    $steamAccount->user->steamApps()
                        ->updateOrCreate(
                            ['steam_app_id' => $ownedGame->appid],
                            [
                                'playtime_forever' => $ownedGame->playtime_forever,
                            ]
                        );
                }

                $recentlyPlayedGames = $steamWebApi->GetRecentlyPlayedGames(
                    steamid: $steamAccount->provider_id,
                );

                foreach ($recentlyPlayedGames as $recentlyPlayedGame) {
                    if (! SteamApp::find($recentlyPlayedGame->appid)) {
                        SteamApp::create(
                            [
                                'id' => $recentlyPlayedGame->appid,
                                'name' => $recentlyPlayedGame->name ?? $recentlyPlayedGame->appid,
                            ]
                        );
                    }
                    $steamAccount->user->steamApps()
                        ->updateOrCreate(
                            ['steam_app_id' => $recentlyPlayedGame->appid],
                            [
                                'playtime_two_weeks' => $recentlyPlayedGame->playtime_2weeks,
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
