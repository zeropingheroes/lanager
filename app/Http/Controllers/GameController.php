<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Services\GetActiveGamesService;
use Zeropingheroes\Lanager\Services\GetGamesOwnedService;
use Zeropingheroes\Lanager\Services\GetGamesPlayedRecentlyService;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gamesBeingPlayed = (new GetActiveGamesService())->get();
        $gamesPlayedRecently = (new GetGamesPlayedRecentlyService())->get();
        $gamesOwned = (new GetGamesOwnedService())->get();

        return View::make('pages.games.index')
            ->with('liveGames', $gamesBeingPlayed)
            ->with('recentGames', $gamesPlayedRecently)
            ->with('ownedGames', $gamesOwned);
    }

}
