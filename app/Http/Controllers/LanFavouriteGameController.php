<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\Services\GetLanFavouriteGamesService;

class LanFavouriteGameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Lan $lan
     * @return \Illuminate\Contracts\View\View
     * @internal param Request $request
     */
    public function index(Lan $lan)
    {
        $lanFavourites = (new GetLanFavouriteGamesService($lan))->get();

        if(Auth::user()) {
            $userFavourites = Auth::user()->favouriteGames()->where('lan_id', $lan->id)->get();
        } else {
            $userFavourites = collect();
        }

        return View::make('pages.lans.favourite-games.index')
            ->with('lan', $lan)
            ->with('lanFavourites', $lanFavourites)
            ->with('userFavourites', $userFavourites);
    }

}
