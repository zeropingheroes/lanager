<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\Game;
use Zeropingheroes\Lanager\Requests\StoreUserFavouriteGameRequest;
use Zeropingheroes\Lanager\User;
use Zeropingheroes\Lanager\UserFavouriteGame;
use Illuminate\Http\Request;

class UserFavouriteGameController extends Controller
{
    /**
     * UserFavouriteGameController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['only' => ['store']]);
    }

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

    /**
     * Store a newly created resource in storage.
     *
     * @param User $user
     * @param Request $httpRequest
     * @return \Illuminate\Http\Response
     */
    public function store(User $user, Request $httpRequest)
    {
        $this->authorize('create', [UserFavouriteGame::class, $user]);

        $input = [
            'id' => $httpRequest->input('id'),
            'provider' => $httpRequest->input('provider'),
            'user' => $user,
        ];

        $request = new StoreUserFavouriteGameRequest($input);

        if ($request->invalid()) {
            return response(['errors' => $request->errors()], 400);
        }
        $favouriteGame = $user->favouriteGames()->create(['favouriteable_id' => $input['id'], 'favouriteable_type' => $input['provider']]);
        if($favouriteGame->exists) {
            return response(null, 201);
        }
    }

}
