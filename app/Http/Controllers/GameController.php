<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Services\GetActiveGamesService;
use Zeropingheroes\Lanager\Services\GetGamesOwnedService;
use Zeropingheroes\Lanager\Services\GetGamesPlayedRecentlyService;

class GameController extends Controller
{
    /**
     * Display games in progress.
     */
    public function inProgress(): ViewContract
    {
        $games = (new GetActiveGamesService)->get();

        return View::make('pages.games.in-progress')
            ->with('games', $games);
    }

    /**
     * Display recently played games.
     */
    public function recent(): ViewContract
    {
        $games = (new GetGamesPlayedRecentlyService)->get();

        return View::make('pages.games.recent')
            ->with('games', $games);
    }

    /**
     * Display games owned.
     */
    public function owned(): ViewContract
    {
        $games = (new GetGamesOwnedService)->get();

        return View::make('pages.games.owned')
            ->with('games', $games);
    }
}
