<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Illuminate\Http\Request;
use Response;
use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\Game;
use Zeropingheroes\Lanager\SteamApp;
use Zeropingheroes\Lanager\BlizzardGame;

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
        if (!$request->has('name')) {
            return Response::json(
                [
                    'errors' => [
                        'The name parameter is required',
                    ],
                ],
                400
            );
        }

        // Find Blizzard games
        $blizzardGames = BlizzardGame::where('name', 'like', '%' . $request->name . '%')
            ->limit(50)
            ->get();

        $blizzardGames->map(function ($blizzardGame) {
            $blizzardGame['id_type'] = 'blizzard_game';
            return $blizzardGame;
        });


        // Find Steam games
        $steamApps = SteamApp::where('type', '=', 'game')
            ->where('name', 'like', '%' . $request->name . '%')
            ->limit(50)
            ->get();

        $steamApps->map(function ($steamApp) {
            $steamApp['id_type'] = 'steam_app';
            return $steamApp;
        });

        // Merge collections, showing Blizzard games first
        $games = $blizzardGames->merge($steamApps);

        $limit = ($request->limit < 50) ? $request->limit : 10;

        $games = $games->take($limit);

        return Game::collection($games);
    }
}
