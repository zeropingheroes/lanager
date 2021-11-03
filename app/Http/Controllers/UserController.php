<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Session;
use View;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\User;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  User $user
     * @return \Illuminate\Contracts\View\View
     */
    public function show(User $user)
    {
        $lansAttended = $user->lans;
        $gamesOwned = new Collection();
        $gamesInCommon = new Collection();
        $gameSessions = new Collection();

        // Get the LAN happening now, or the most recently ended LAN
        $lan = Lan::presentAndPast()
            ->orderBy('start', 'desc')
            ->first();

        // If the user's apps are visible, and they're attending the current LAN (or there isn't a current LAN)
        if (
            ($user->steamMetadata && $user->steamMetadata->apps_visible == 1)
            && (! $lan || $lansAttended->contains('id', $lan->id))
        ) {
            // Get games in common so long as the logged in user is not viewing their own profile
            if (Auth::check() && $user->id != Auth::user()->id) {
                $authUserGames = Auth::user()
                    ->steamApps()
                    ->where('playtime_forever', '<>', 0)
                    ->pluck('steam_app_id')->toArray();

                $gamesInCommon = $user->steamApps()
                    ->with('app')
                    ->where('playtime_forever', '<>', 0)
                    ->whereIn('steam_app_id', $authUserGames)
                    ->orderBy('playtime_forever', 'desc')
                    ->paginate(5, ['*'], 'gamesInCommon');
            }

            // Get games owned by the user
            $gamesOwned = $user->steamApps()
                ->with('app')
                ->where('playtime_forever', '<>', 0)
                ->orderBy('playtime_forever', 'desc')
                ->paginate(5, ['*'], 'gamesOwned');

            // If there's a LAN, get the games the user
            // has played during the LAN
            if ($lan) {
                $gameSessions = $user->steamAppSessions()
                    ->orderBy('start', 'desc')
                    ->where('start', '>', $lan->start)
                    ->paginate(5, ['*'], 'gameSessions');
            }
        }

        return View::make('pages.users.show')
            ->with('user', $user)
            ->with('gamesOwned', $gamesOwned)
            ->with('gamesInCommon', $gamesInCommon)
            ->with('lansAttended', $lansAttended)
            ->with('gameSessions', $gameSessions)
            ->with('currentLan', $lan);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $user
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        User::destroy($user->id);

        if ($user->id === Auth::user()->id) {
            Auth::logout();
        }

        Session::flash(
            'success',
            trans('phrase.item-name-deleted', ['item' => trans('title.user'), 'name' => $user->username])
        );

        return redirect()->route('users');
    }
}
