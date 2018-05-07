<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\SteamUserState;

class GamesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('pages.game.index')
            ->with('games', $this->liveGames());
    }

    /**
     * Get the games that are currently in progress
     *
     * @return array
     */
    private function liveGames(): array {
        $start = Carbon::createFromTimeStamp(time() - (60));
        $end = Carbon::createFromTimeStamp(time() + (60));

        $states = SteamUserState::select(['steam_user_states.user_id', 'steam_user_states.steam_app_id', 'steam_user_states.steam_app_server_id'])
            ->join(
                DB::raw('(
										SELECT max(created_at) max_created_at, user_id
										FROM steam_user_states
										WHERE (created_at
											BETWEEN "'.$start.'"
											AND 	"'.$end.'")
											AND (steam_app_id IS NOT NULL)
										GROUP BY user_id
										) s2'),
                function ($join) {
                    $join->on('steam_user_states.user_id', '=', 's2.user_id')
                        ->on('steam_user_states.created_at', '=', 's2.max_created_at');
                }
            )
            ->orderBy('steam_user_states.user_id')
            ->with('user', 'app')
            ->get();

        if (count($states)) {

            // Collect and combine states for the same game
            foreach ($states as $state) {

                // merge states that refer to the same game
                $combinedUsage[$state->steam_app_id]['game'] = $state->app;

                // add the state's user as a child of the above game key
                $combinedUsage[$state->steam_app_id]['users'][] = $state->user;
            }

            // Build clean array of games
            foreach ($combinedUsage as $usageItem) {
                $usageItem['game']['users'] = $usageItem['users'];
                $games[] = $usageItem['game'];
            }

            // Sort games array by user count, in descending order
            usort($games, function ($a, $b) {
                return count($b['users']) - count($a['users']);
            });
        } else {
            $games = [];
        }
        return $games;
    }

}
