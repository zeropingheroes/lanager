<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\Services\CurrentLanAttendeesService;
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
        // If there is not a current LAN
        if (! Cache::get('currentLan')) {
            // Show all users
            $users = User::orderBy('username')->get();
        } else {
            // Otherwise show users who are attending the current LAN
            $users = Cache::get('currentLan')->users()->orderBy('username')->get();
        }

        $users->load('state', 'state.app', 'state.server', 'OAuthAccounts', 'SteamApps', 'SteamMetadata', 'lans');

        return View::make('pages.users.index')
            ->with('users', $users);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\User $user
     * @return \Illuminate\Contracts\View\View
     */
    public function show(User $user)
    {
        $lansAttended = $user->lans;
        $gamesOwned = new Collection();
        $gamesInCommon = new Collection();

        // If there isn't a current LAN, or there is a current LAN
        // and the user whose profile is being viewed is attending it
        if ( ! Cache::get('currentLan') || $lansAttended->contains('id', Cache::get('currentLan')->id)) {

            // Get games in common so long as the logged
            // in user is not viewing their own profile
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
            }

            // Get games owned by the user
            $gamesOwned = $user->SteamApps()
                ->with('app')
                ->where('playtime_forever', '<>', 0)
                ->orderBy('playtime_forever', 'desc')
                ->paginate(5, ['*'], 'gamesOwned');
        }

        return View::make('pages.users.show')
            ->with('user', $user)
            ->with('gamesOwned', $gamesOwned)
            ->with('gamesInCommon', $gamesInCommon)
            ->with('lansAttended', $lansAttended);
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
