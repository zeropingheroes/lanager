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

        // Get the LAN happening now, or the most recently ended LAN
        $lan = Lan::presentAndPast()
            ->orderBy('start', 'desc')
            ->first();

        // If the user's apps are visible, and they're attending the current LAN (or there isn't a current LAN)
        if (($user->SteamMetadata && $user->SteamMetadata->apps_visible == 1) &&
            (! $lan || $lansAttended->contains('id', $lan->id))) {

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
            ->with('lansAttended', $lansAttended)
            ->with('currentLan', $lan);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Zeropingheroes\Lanager\User $user
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        User::destroy($user->id);

        if ($user->id === Auth::user()->id) {
            Auth::logout();
        }

        return redirect()
            ->route('users.index')
            ->withSuccess(__('phrase.user-successfully-deleted', ['username' => $user->username]));
    }
}
