<?php

namespace Zeropingheroes\Lanager\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;
use Syntax\SteamApi\Facades\SteamApi as SteamApi;
use Throwable;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\SteamUserAppSession;
use Zeropingheroes\Lanager\User;
use Zeropingheroes\Lanager\UserOAuthAccount;

class UpdateSteamUsersService
{
    /**
     * Steam ID(s) to be updated.
     *
     * @var array
     */
    protected $steamIds = [];

    /**
     * Errors.
     *
     * @var MessageBag
     */
    protected $errors;

    /**
     * Successfully updated Steam IDs.
     *
     * @var array
     */
    protected $updated = [];

    /**
     * Steam IDs that were not updated due to failures.
     *
     * @var array
     */
    protected $failed = [];

    /**
     * User IDs who are attending the current LAN.
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
            throw new Exception(trans('phrase.one-or-more-steam-ids-must-be-provided'));
        }

        // Ensure we have an array, even if only one ID is given
        $steamIds = (array) $steamIds;

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
     * @return MessageBag
     */
    public function errors(): MessageBag
    {
        return $this->errors;
    }

    /**
     * Update Steam users.
     * @return void
     * @throws Throwable
     */
    public function update(): void
    {
        $this->endStaleAppSessions();

        $steamUsers = SteamApi::user($this->steamIds)->GetPlayerSummaries();

        // Get the LAN happening now, or the most recently ended LAN
        $lan = Lan::presentAndPast()
            ->orderBy('start', 'desc')
            ->first();

        if ($lan) {
            $this->currentLanAttendees = $lan->users;
        }

        // Update state for each user in turn
        foreach ($steamUsers as $steamUser) {
            try {
                if ($this->updateUser($steamUser)) {
                    $this->updated[$steamUser->steamId] = $steamUser->personaName;
                }
            } catch (Exception $e) {
                $this->failed[$steamUser->steamId] = $steamUser->personaName;
                $this->errors->add(
                    $steamUser->steamId,
                    trans(
                        'phrase.unable-to-update-data-for-user-x',
                        ['x' => $steamUser->personaName, 'error' => $e->getMessage()]
                    )
                );
            }
        }
    }

    /**
     * Update a single Steam user.
     *
     * @param $steamUser
     * @return bool
     * @throws Throwable
     */
    protected function updateUser($steamUser): bool
    {
        // Check if the Steam account already exists in the database
        $userOAuthAccount = UserOAuthAccount::where('provider_id', $steamUser->steamId)->first();

        // If this Steam account is not already in the database
        if (! $userOAuthAccount) {
            // Create a new LANager user account
            $user = User::create(['username' => $steamUser->personaName]);
        } else {
            // Otherwise just get the associated user
            $user = $userOAuthAccount->user;
        }

        // Create or update the user's existing linked OAuth account for Steam
        $user->accounts()
            ->updateOrCreate(
                [
                    'provider' => 'steam',
                    'provider_id' => $steamUser->steamId,
                ],
                [
                    'username' => $steamUser->personaName,
                    'avatar' => $steamUser->avatarMediumUrl,
                ]
            );

        // Update the user's Steam account metadata
        $user->steamMetadata()->updateOrCreate(
            [],
            [
                'steam_user_status_code_id' => $steamUser->personaStateId,
                'profile_visible' => ($steamUser->communityVisibilityState == 3),
                'profile_updated_at' => now(),
            ]
        );

        // Do not record gameplay info, unless a LAN is in progress
        if (! $this->currentLanAttendees) {
            return true;
        }

        // Do not record gameplay info if the user is not at the LAN in progress
        if (! $this->currentLanAttendees->contains('id', $user->id)) {
            return true;
        }

        // If the user is running an app/game
        if ($steamUser->gameDetails) {
            // Get existing ongoing session for the game
            // or if none exists instantiate a new
            $session = $user->steamAppSessions()->firstOrNew(
                [
                    'end' => null,
                    'steam_app_id' => $steamUser->gameDetails->gameId,
                ]
            );

            // If no existing ongoing session was found
            if (! $session->exists) {
                // Create one starting now
                $session->start = Carbon::now();

                return $session->saveOrFail();
                // If an existing ongoing session was found
            } else {
                // Update its updated_at timestamp field
                return $session->touch();
            }
            // If the user is not running an app/game
        } else {
            // Add an end time to any sessions without one
            $user->steamAppSessions()
                ->whereNull('end')
                ->update(['end' => Carbon::now()]);
        }

        return true;
    }

    /**
     * End any unfinished sessions that have
     * not been updated in the last X minutes.
     *
     * @return mixed
     */
    private function endStaleAppSessions()
    {
        return SteamUserAppSession::where('updated_at', '<', Carbon::now()->subMinutes(10))
            ->whereNull('end')
            ->update(['end' => Carbon::now()]);
    }
}
