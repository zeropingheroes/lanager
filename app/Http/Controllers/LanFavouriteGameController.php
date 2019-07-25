<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Http\Resources\Game;
use Zeropingheroes\Lanager\Lan;

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
        $favourites = $lan->userFavouriteGames()->with('user', 'favouriteable')->get();

        if (empty($favourites)) {
            return new Collection();
        }

        // Collect and combine favourites for the same game
        $combined = [];
        foreach ($favourites as $favourite) {
            $combined[$favourite->favouriteable_id] = $combined[$favourite->favouriteable_id] ?? ['game' => null, 'users' => []];
            $combined[$favourite->favouriteable_id]['game'] = $combined[$favourite->favouriteable_id]['game'] ?? $favourite->favouriteable;
            $combined[$favourite->favouriteable_id]['users'][] = $favourite->user;
        }

        // Sort games array by user count, in descending order (removing key)
        usort(
            $combined,
            function ($a, $b) {
                return count($b['users']) - count($a['users']);
            }
        );
        return View::make('pages.lans.favourite-games.index')
            ->with('lan', $lan)
            ->with('favourites', $combined);
    }

}
