<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\View;
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
        $favourites = (new GetLanFavouriteGamesService($lan))->get();

        return View::make('pages.lans.favourite-games.index')
            ->with('lan', $lan)
            ->with('favourites', $favourites);
    }

}
