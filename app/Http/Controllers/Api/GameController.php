<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Illuminate\Http\Request;
use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\Game;
use Zeropingheroes\Lanager\SteamApp;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        if ($request->has('limit') && $request->limit <= 50) {
            $limit = $request->limit;
        } else {
            $limit = 10;
        }
        $games = SteamApp::limit($limit);

        if ($request->filled('name')) {
            $games->where('name', 'like', '%'.$request->name.'%');
        }

        return Game::collection($games->get());
    }

    /**
     * Display the specified resource.
     *
     * @param SteamApp $game
     * @return Game
     */
    public function show(SteamApp $game)
    {
        return new Game($game);
    }
}
