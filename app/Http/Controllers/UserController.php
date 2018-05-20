<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // If the user has requested to see historic users,
        // or there are no LANs, get all users
        if ($request->has('historic') || Lan::count() == 0) {
            $users = User::orderBy('username')->get();
        } else {
            // If there's a LAN happening now, get it
            $lan = Lan::happeningNow()->first();

            // Otherwise, get the most recent past LAN
            if ($lan->count() == 0) {
                $lan = Lan::past()->first();
            }

            // Get all of the LAN's attendees
            $users = $lan->users()->get();
        }

        $users->load('state.app', 'state.server', 'OAuthAccounts', 'SteamApps');

        return View::make('pages.users.index')
            ->with('users', $users);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if (Auth::check() && $user->id != Auth::user()->id) {
            $authUserGames = Auth::user()
                ->SteamApps()
                ->where('playtime_forever', '<>', 0)
                ->pluck('steam_app_id')->toArray();

            $gamesInCommon = $user->SteamApps()
                ->with('app')
                ->where('playtime_forever', '<>', 0)
                ->whereIn('steam_app_id', $authUserGames)
                ->orderBy('playtime_forever', 'desc')
                ->paginate(5, ['*'], 'gamesInCommon');
        } else {
            $gamesInCommon = new Collection();
        }

        $gamesOwned = $user->SteamApps()
            ->with('app')
            ->where('playtime_forever', '<>', 0)
            ->orderBy('playtime_forever', 'desc')
            ->paginate(5, ['*'], 'gamesOwned');

        return View::make('pages.users.show')
            ->with('user', $user)
            ->with('gamesOwned', $gamesOwned)
            ->with('gamesInCommon', $gamesInCommon);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Zeropingheroes\Lanager\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
