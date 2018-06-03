<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\ActiveGame;
use Zeropingheroes\Lanager\Services\GetActiveGamesService;

class ActiveGamesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activeGames = (new GetActiveGamesService())->get();

        return ActiveGame::collection($activeGames);
    }
}
