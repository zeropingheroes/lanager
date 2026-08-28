<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;
use Throwable;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\SteamUserAppSession;
use Zeropingheroes\Lanager\Models\User;
use Zeropingheroes\Lanager\Models\UserOAuthAccount;
use Zeropingheroes\SteamApis\SteamWebApi\Enums\CommunityVisibilityState;
use Zeropingheroes\SteamApis\SteamWebApi\SteamWebApiConnector;

class UpdateSteamUsersService
{
    /**
     * Steam ID(s) to be updated.
     */
    protected array $steamIds = [];

    /**
     * Errors.
     */
    protected MessageBag $errors;

    /**
     * Successfully updated Steam IDs.
     */
    protected array $updated = [];

    /**
     * Steam IDs that were not updated due to failures.
     */
    protected array $failed = [];

    /**
     * User IDs who are attending the current LAN.
     */
    private Collection $currentLanAttendees;

    /**
     * Construct the class
     *
     * @throws Exception
     */
    public function __construct(array $steamIds)
    {
        if ($steamIds === []) {
            throw new Exception(trans('phrase.one-or-more-steam-ids-must-be-provided'));
        }

        // Remove excess white space and convert strings to integers
        $steamIds = array_map(
            fn ($steamId) => intval(trim((string) $steamId)),
            $steamIds
        );

        $this->steamIds = $steamIds;

        $this->errors = new MessageBag;
    }

    /**
     * Get the users who were updated.
     */
    public function getUpdated(): array
    {
        return $this->updated;
    }

    /**
     * Get the users who were not updated.
     */
    public function getFailed(): array
    {
        return $this->failed;
    }

    /**
     * Get the errors
     */
    public function errors(): MessageBag
    {
        return $this->errors;
    }

    /**
     * Update the Steam users in the database.
     *
     * @throws Throwable
     */
    public function update(): void
    {
        $this->endStaleAppSessions();

        $steamWebApiConnector = app(SteamWebApiConnector::class);

        $steamUsers = $steamWebApiConnector->GetPlayerSummaries($this->steamIds);

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
                    $this->updated[$steamUser->steamid] = $steamUser->personaname;
                }
            } catch (Exception $e) {
                $this->failed[$steamUser->steamid] = $steamUser->personaname;
                $this->errors->add(
                    $steamUser->steamid,
                    trans(
                        'phrase.unable-to-update-data-for-user-x',
                        ['x' => $steamUser->personaname, 'error' => $e->getMessage()]
                    )
                );
            }
        }
    }

    /**
     * Update a single Steam user.
     *
     * @throws Throwable
     */
    protected function updateUser($steamUser): bool
    {
        // Check if the Steam account already exists in the database
        $userOAuthAccount = UserOAuthAccount::where('provider_id', $steamUser->steamid)->first();

        // If this Steam account is not already in the database
        if (! $userOAuthAccount) {
            // Create a new LANager user account
            $user = User::create(['username' => $steamUser->personaname]);
        } else {
            // Otherwise just get the associated user
            $user = $userOAuthAccount->user;
        }

        // Create or update the user's existing linked OAuth account for Steam
        $user->accounts()
            ->updateOrCreate(
                [
                    'provider' => 'steam',
                    'provider_id' => $steamUser->steamid,
                ],
                [
                    'username' => $steamUser->personaname,
                    'avatar' => $steamUser->avatarmedium,
                ]
            );

        // Update the user's Steam account metadata
        $user->steamMetadata()->updateOrCreate(
            [],
            [
                'steam_user_status_code_id' => $steamUser->personastate,
                'profile_visible' => ($steamUser->communityvisibilitystate == CommunityVisibilityState::Public),
                'profile_updated_at' => now(),
            ]
        );

        // Do not record gameplay info, unless a LAN is in progress
        if (! isset($this->currentLanAttendees)) {
            return true;
        }

        // Do not record gameplay info if the user is not at the LAN in progress
        if (! $this->currentLanAttendees->contains('id', $user->id)) {
            return true;
        }

        // If the user is running an app/game
        if (isset($steamUser->gameid)) {
            // Get existing ongoing session for the game
            // or if none exists instantiate a new
            $session = $user->steamAppSessions()->firstOrNew(
                [
                    'end' => null,
                    'steam_app_id' => $steamUser->gameid,
                ]
            );

            // If no existing ongoing session was found
            if (! $session->exists) {
                // Create one starting now
                $session['start'] = Carbon::now();

                return $session->saveOrFail();
            }

            // If an existing ongoing session was found
            // Update its updated_at timestamp field
            return $session->touch();
        }

        // If the user is not running an app/game, add an end time to any sessions without one
        $user->steamAppSessions()
            ->whereNull('end')
            ->update(['end' => Carbon::now()]);

        return true;
    }

    /**
     * End any unfinished sessions that have not been updated in the last X minutes.
     */
    private function endStaleAppSessions(): void
    {
        SteamUserAppSession::where('updated_at', '<', Carbon::now()->subMinutes(10))
            ->whereNull('end')
            ->update(['end' => Carbon::now()]);
    }
}
