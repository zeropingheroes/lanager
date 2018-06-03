<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Illuminate\Http\Request;
use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\ActiveGame;
use Zeropingheroes\Lanager\Services\GetActiveGamesService;

class ActiveGamesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $activeGames = (new GetActiveGamesService())->get();

        if ($request->filled('limit')) {
            $activeGames = $activeGames->take($request->limit);
        }

        return ActiveGame::collection($activeGames);
    }
}
