<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Response;
use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\Game;
use Zeropingheroes\Lanager\OriginGame;
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
            $blizzardGame['id_type'] = 'blizzard';
            return $blizzardGame;
        });

        // Find Origin games
        $originGames = OriginGame::where('name', 'like', '%' . $request->name . '%')
            ->limit(50)
            ->get();

        $originGames->map(function ($originGame) {
            $originGame['id_type'] = 'origin';
            return $originGame;
        });

        // Find Steam games
        $steamApps = SteamApp::where('type', '=', 'game')
            ->where('name', 'like', '%' . $request->name . '%')
            ->limit(50)
            ->get();

        $steamApps->map(function ($steamApp) {
            $steamApp['id_type'] = 'steam';
            return $steamApp;
        });

        // Merge collections
        // TODO: Make games in Steam overwrite other games of the same name elsewhere
        $games = new Collection();
        $games = $games->merge($blizzardGames);
        $games = $games->merge($steamApps);
        $games = $games->merge($originGames);

        $limit = ($request->limit < 50) ? $request->limit : 10;

        $games = $games->take($limit);

        return Game::collection($games);
    }
}
