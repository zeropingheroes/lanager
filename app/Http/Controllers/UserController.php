<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('state.app', 'state.server', 'OAuthAccounts', 'SteamApps')->orderBy('username')->get();
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
                ->paginate(5);
        } else {
            $gamesInCommon = [];
        }

        return View::make('pages.users.show')
            ->with('user', $user)
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
