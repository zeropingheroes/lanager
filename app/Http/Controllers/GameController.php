<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\SteamUserApp;
use Zeropingheroes\Lanager\SteamUserState;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('pages.games.index')
            ->with('liveGames', $this->liveGames())
            ->with('recentGames', $this->recentGames())
            ->with('ownedGames', $this->ownedGames());
    }

    /**
     * Get the games that are currently in progress
     *
     * @return array
     */
    private function liveGames(): array
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

    /**
     * Get the top 10 games that users have played in the last 2 weeks
     *
     * @return array
     */
    private function recentGames(): array
    {
        $steamUserApps = SteamUserApp::with('user', 'app', 'user.state', 'user.OAuthAccounts')
            ->where('playtime_two_weeks', '>', 0)
            ->get();

        if (empty($steamUserApps)) {
            return [];
        }

        // Collect and combine games
        $combinedUsage = [];
        foreach ($steamUserApps as $steamUserApp) {
            $combinedUsage[$steamUserApp->steam_app_id] = $combinedUsage[$steamUserApp->steam_app_id] ?? [
                    'game' => null,
                    'users' => []
                ];
            $combinedUsage[$steamUserApp->steam_app_id]['game'] = $combinedUsage[$steamUserApp->steam_app_id]['game'] ?? $steamUserApp->app;
            $combinedUsage[$steamUserApp->steam_app_id]['users'][] = $steamUserApp->user;
        }

        // Sort games array by user count, in descending order
        usort(
            $combinedUsage,
            function ($a, $b) {
                return count($b['users']) - count($a['users']);
            }
        );

        // TODO: sort users by play time

        // Remove any recently played games that have only been played by one user
        $combinedUsage = array_filter(
            $combinedUsage,
            function ($game) {
                return count($game['users']) > 1;
            }
        );

        $combinedUsage = array_slice($combinedUsage, 0, 10);

        return $combinedUsage;
    }

    /**
     * Get the top 10 games that users own
     *
     * @return array
     */
    private function ownedGames(): array
    {
        $steamUserApps = SteamUserApp::with('user', 'app', 'user.state', 'user.OAuthAccounts')
            ->where('playtime_forever', '>', 60)->get();

        if (empty($steamUserApps)) {
            return [];
        }

        // Collect and combine games
        $combinedUsage = [];
        foreach ($steamUserApps as $steamUserApp) {
            $combinedUsage[$steamUserApp->steam_app_id] = $combinedUsage[$steamUserApp->steam_app_id] ?? [
                    'game' => null,
                    'users' => []
                ];
            $combinedUsage[$steamUserApp->steam_app_id]['game'] = $combinedUsage[$steamUserApp->steam_app_id]['game'] ?? $steamUserApp->app;
            $combinedUsage[$steamUserApp->steam_app_id]['users'][] = $steamUserApp->user;
        }

        // Sort games array by user count, in descending order
        usort(
            $combinedUsage,
            function ($a, $b) {
                return count($b['users']) - count($a['users']);
            }
        );

        // Remove any recently played games that have only been played by one user
        $combinedUsage = array_filter(
            $combinedUsage,
            function ($game) {
                return count($game['users']) > 1;
            }
        );

        $combinedUsage = array_slice($combinedUsage, 0, 10);

        return $combinedUsage;
    }
}
