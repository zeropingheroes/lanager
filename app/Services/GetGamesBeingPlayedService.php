<?php

namespace Zeropingheroes\Lanager\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Zeropingheroes\Lanager\SteamUserState;

class GetGamesBeingPlayedService
{
    /**
     * Get the games that are currently being played
     *
     * @return array
     */
    public function get(): array
    {
        $states = SteamUserState::select('*')
            ->join(
                DB::raw(
                    '(SELECT user_id, MAX(created_at) latest_date
                            FROM steam_user_states
                            WHERE created_at
                            BETWEEN "' . (Carbon::now()->subMinute()) . '"
                            AND 	"' . (Carbon::now()) . '"
                            GROUP BY user_id
							) latest'
                ),
                function ($join) {
                    $join->on('steam_user_states.user_id', '=', 'latest.user_id')
                        ->on('steam_user_states.created_at', '=', 'latest.latest_date');
                }
            )
            ->whereNotNull('steam_app_id')
            ->orderBy('steam_user_states.user_id')
            ->with('user', 'app', 'user.OAuthAccounts', 'user.state')
            ->get();

        if (empty($states)) {
            return [];
        }

        // Collect and combine states for the same game
        // TODO: build collection instead of array
        $combinedUsage = [];
        foreach ($states as $state) {
            $combinedUsage[$state->steam_app_id] = $combinedUsage[$state->steam_app_id] ?? ['game' => null, 'users' => []];
            $combinedUsage[$state->steam_app_id]['game'] = $combinedUsage[$state->steam_app_id]['game'] ?? $state->app;
            $combinedUsage[$state->steam_app_id]['users'][] = $state->user;
        }

        // Sort games array by user count, in descending order
        usort(
            $combinedUsage,
            function ($a, $b) {
                return count($b['users']) - count($a['users']);
            }
        );

        return $combinedUsage;
    }
}