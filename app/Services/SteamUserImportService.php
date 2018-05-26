<?php

namespace Zeropingheroes\Lanager\Services;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\MessageBag;
use Steam;
use Zeropingheroes\Lanager\UserOAuthAccount;
use Zeropingheroes\Lanager\SteamApp;
use Zeropingheroes\Lanager\SteamAppServer;
use Zeropingheroes\Lanager\SteamUserState;
use Zeropingheroes\Lanager\User;

class SteamUserImportService
{

    /**
     * Steam ID(s) to be imported
     *
     * @var array
     */
    protected $steamIds = [];

    /**
     * Errors
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * Successfully imported Steam IDs
     *
     * @var array
     */
    protected $imported = [];

    /**
     * Steam IDs that were not imported due to failures
     *
     * @var array
     */
    protected $failed = [];

    /**
     * User IDs who are attending the current LAN
     * @var Collection
     */
    private $currentLanAttendees;

    /**
     * @param array|int $steamIds
     * @throws Exception
     */
    public function __construct($steamIds)
    {
        if (empty($steamIds)) {
            throw new Exception(__('phrase.no-steam-users-to-import'));
        }

        // Ensure we have an array, even if only one ID is given
        $steamIds = (array)$steamIds;

        // Remove excess white space and convert strings to integers
        $steamIds = array_map(
            function ($steamId) {
                return intval(trim($steamId));
            },
            $steamIds
        );

        $this->steamIds = $steamIds;
        $this->errors = new MessageBag();
    }

    /**
     * @return array
     */
    public function getImported(): array
    {
        return $this->imported;
    }

    /**
     * @return array
     */
    public function getFailed(): array
    {
        return $this->failed;
    }

    /**
     * Import errors
     *
     * @return MessageBag
     */
    public function errors(): MessageBag
    {
        return $this->errors;
    }

    /**
     * Import Steam users
     * @return void
     * @throws \Throwable
     */
    public function import(): void
    {
        $steamUsers = Steam::user($this->steamIds)->GetPlayerSummaries();
        $this->currentLanAttendees = Cache::get('currentLan')->users;

        // Import state for each user in turn
        foreach ($steamUsers as $steamUser) {

            try {
                if ($this->importUser($steamUser)) {
                    $this->imported[] = $steamUser->steamId;
                }

            } catch (Exception $e) {
                $this->failed[] = $steamUser->steamId;
                $this->errors->add(
                    $steamUser->steamId,
                    __('phrase.unable-to-import-state-for-user', ['id' => $steamUser->steamId, 'error' => $e->getMessage()])
                );
            }
        }
    }

    /**
     * Import a single Steam user
     *
     * @param $steamUser
     * @return bool
     * @throws \Throwable
     */
    protected function importUser($steamUser): bool
    {
        // Create a new state
        $steamUserState = new SteamUserState;

        // Check if the Steam account already exists in the database
        $userOAuthAccount = UserOAuthAccount::where('provider_id', $steamUser->steamId)->first();

        if (!$userOAuthAccount) {
            // If this Steam account is not already in the database
            // Create a new LANager user account
            $user = User::create(['username' => $steamUser->personaName]);

        } else {
            // If this Steam account is in the database
            // Get the associated user
            $user = $userOAuthAccount->user;
        }

        // Update the existing Steam account
        // or create it, if it does not yet exist
        $userOAuthAccount = $user->OAuthAccounts()
            ->updateOrCreate(
                [
                    'username' => $steamUser->personaName,
                    'avatar' => $steamUser->avatarMediumUrl,
                    'provider' => 'steam',
                    'provider_id' => $steamUser->steamId,
                ]
            );

        $profileVisible = ($steamUser->communityVisibilityState == 3);

        $user->SteamMetadata()->updateOrCreate(
            [],
            [
                'profile_visible' => $profileVisible,
                'profile_updated_at' => now()
            ]
        );

        // If the user is not attending the current LAN
        // do not create a state for them
        if ($this->currentLanAttendees->contains('user_id', $user->id)) {
            return true;
        }

        // Associate the state with the user
        $steamUserState->user()->associate($userOAuthAccount->user);

        // Get the app they are running, if any
        if ($steamUser->gameDetails) {
            $steamApp = SteamApp::find($steamUser->gameDetails->gameId);

            // Associate the state with the app
            $steamUserState->app()->associate($steamApp);
        }

        // Get the server they are connected to, if any
        if ($steamUser->gameDetails && $steamUser->gameDetails->serverIp) {

            preg_match('/(.*)((?::))((?:[0-9]+))$/', $steamUser->gameDetails->serverIp, $matches);
            $port = $matches[3];
            $ip = $matches[1];

            // Get the server
            // ... or if the server has not been previously recorded, create it
            $steamAppServer = SteamAppServer::firstOrCreate(
                [
                    'steam_app_id' => $steamApp->id,
                    'address' => $ip,
                    'port' => $port
                ]
            );

            // Associate the state with the server
            $steamUserState->server()->associate($steamAppServer);
        }

        // Set the user's online status
        $steamUserState->steam_user_status_code_id = $steamUser->personaStateId;

        return $steamUserState->saveOrFail();
    }
}