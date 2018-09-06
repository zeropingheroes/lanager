<?php

namespace Zeropingheroes\Lanager\Services;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Zeropingheroes\Lanager\SteamUserAppSession;
use Zeropingheroes\Lanager\SteamUserState;
use Illuminate\Support\Collection;

class GetGamesPlayedBetweenService
{
    /**
     * @var Carbon
     */
    protected $start;

    /**
     * @var Carbon
     */
    protected $end;

    /**
     * GetGamesPlayedBetweenService constructor.
     * @param Carbon $start
     * @param Carbon $end
     */
    public function __construct(Carbon $start, Carbon $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * Get the games that were played between two dates
     *
     * @return Collection
     */
    public function get(): Collection
    {
        $sessions = SteamUserAppSession::where('start', '>', $this->start)
            ->where(
                function ($query) {
                    $query->where('end', '<', $this->end)
                        ->orWhere('updated_at', '<', $this->end); // include games without an end time
                }
            )
            ->with('user', 'app', 'user.accounts', 'user.steamMetadata')
            ->get();

        if ($sessions->isEmpty()) {
            return new Collection();
        }

        // Collect and combine sessions for the same game
        $combinedUsage = [];
        foreach ($sessions as $session) {
            // Initialise entry if this game has not been added before
            $combinedUsage[$session->steam_app_id] = $combinedUsage[$session->steam_app_id] ?? [
                    'game' => $session->app,
                    'users' => [],
                    'playtime' => new CarbonInterval(0),
                ];

            // Add the session's user to the list of users
            $combinedUsage[$session->steam_app_id]['users'][$session->user->id] = $session->user;

            // Add the session's playtime to the total for this game
            $end = $session->end ?? now();
            $combinedUsage[$session->steam_app_id]['playtime']->add($session->start->diffAsCarbonInterval($end));
        }

        // Obtain a list of columns
        foreach ($combinedUsage as $key => $row) {
            $users[$key] = $row['users'];
            $playtime[$key] = $row['playtime'];
        }

        // Sort games by user count, and then by playtime
        array_multisort(
            array_map('count', $users),
            SORT_NUMERIC,
            SORT_DESC,
            array_map(
                function ($playtime) {
                    return $playtime->totalSeconds;
                },
                $playtime
            ),
            SORT_NUMERIC,
            SORT_DESC,
            $combinedUsage
        );

        return collect($combinedUsage);
    }
}