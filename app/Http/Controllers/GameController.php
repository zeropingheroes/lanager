<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use View;
use Zeropingheroes\Lanager\Services\GetActiveGamesService;
use Zeropingheroes\Lanager\Services\GetGamesOwnedService;
use Zeropingheroes\Lanager\Services\GetGamesPlayedRecentlyService;

class GameController extends Controller
{
    /**
     * Display games in progress.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function inProgress()
    {
        $games = (new GetActiveGamesService())->get();

        return View::make('pages.games.in-progress')
            ->with('games', $games);
    }

    /**
     * Display recently played games.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function recent()
    {
        $games = (new GetGamesPlayedRecentlyService())->get();

        return View::make('pages.games.recent')
            ->with('games', $games);
    }

    /**
     * Display games owned.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function owned()
    {
        $games = (new GetGamesOwnedService())->get();

        return View::make('pages.games.owned')
            ->with('games', $games);
    }
}
