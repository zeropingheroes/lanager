<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Illuminate\Http\Request;
use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\Game;
use Zeropingheroes\Lanager\Http\Resources\User;
use Zeropingheroes\Lanager\Lan;

class LanFavouriteGamesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Lan $lan, Request $request)
    {
        $favourites = $lan->userFavouriteGames;

        if (empty($favourites)) {
            return new Collection();
        }

        // Collect and combine favourites for the same game
        $combined = [];
        foreach ($favourites as $favourite) {
            $combined[$favourite->favouriteable_id] = $combined[$favourite->favouriteable_id] ?? ['game' => null, 'users' => []];
            $combined[$favourite->favouriteable_id]['game'] = $combined[$favourite->favouriteable_id]['game'] ?? new Game($favourite->favouriteable);
            $combined[$favourite->favouriteable_id]['users'][] = [
                'id' => $favourite->user->id,
                'username' => $favourite->user->username,
                'links' => [
                    'self' => route('api.users.show', ['user' => $favourite->user]),
                ],
            ];
        }

        // Sort games array by user count, in descending order (removing key)
        usort(
            $combined,
            function ($a, $b) {
                return count($b['users']) - count($a['users']);
            }
        );
        return collect($combined);

//        if ($request->filled('limit')) {
//            $lanFavouriteGames = $lanFavouriteGames->take($request->limit);
//        }

//            return $lanFavouriteGames;
//        return FavouriteGame::collection($lanFavouriteGames);
    }
}
