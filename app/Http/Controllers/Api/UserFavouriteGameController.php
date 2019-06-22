<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\Game;
use Zeropingheroes\Lanager\User;

class UserFavouriteGameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param User $user
     * @return Game
     */
    public function index(User $user)
    {
        $favouriteGames = $user->favouriteGames;

        $games = $favouriteGames->map(function ($favouriteGame) {
            return $favouriteGame->favouriteable;
        });
        return Game::collection($games);
    }
}
