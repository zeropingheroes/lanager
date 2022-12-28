<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Session;
use View;
use Zeropingheroes\Lanager\Models\Achievement;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\UserAchievement;
use Zeropingheroes\Lanager\Requests\StoreUserAchievementRequest;

class UserAchievementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Zeropingheroes\Lanager\Models\Lan $lan
     * @return \Illuminate\Contracts\View\View
     * @throws AuthorizationException
     */
    public function index(Lan $lan)
    {
        $this->authorize('index', UserAchievement::class);

        $userAchievements = $lan->userAchievements()
            ->with('user', 'achievement')
            ->get();
        $users = $lan->users()
            ->orderBy('username')
            ->get();
        $achievements = Achievement::all();

        return View::make('pages.user-achievements.index')
            ->with('lan', $lan)
            ->with('userAchievements', $userAchievements)
            ->with('users', $users)
            ->with('achievements', $achievements);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $httpRequest
     * @param  \Zeropingheroes\Lanager\Models\Lan $lan
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(Request $httpRequest, Lan $lan)
    {
        $this->authorize('create', UserAchievement::class);

        $input = $httpRequest->only(['user_id', 'achievement_id']);
        $input['lan_id'] = $lan->id;

        $request = new StoreUserAchievementRequest($input);

        if ($request->invalid()) {
            Session::flash('error', $request->errors());

            return redirect()->back()->withInput();
        }

        $userAchievement = UserAchievement::create($input);

        Session::flash(
            'success',
            trans(
                'phrase.achievement-successfully-awarded',
                ['user' => $userAchievement->user->username, 'achievement' => $userAchievement->achievement->name]
            )
        );

        return redirect()->route('lans.user-achievements.index', $lan);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Lan             $lan
     * @param  UserAchievement $userAchievement
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Lan $lan, UserAchievement $userAchievement)
    {
        $this->authorize('delete', $userAchievement);

        // If the user achievement is accessed via the wrong LAN ID, show 404
        if ($userAchievement->lan_id != $lan->id) {
            abort(404);
        }

        UserAchievement::destroy($userAchievement->id);

        Session::flash(
            'success',
            trans(
                'phrase.achievement-successfully-revoked',
                ['user' => $userAchievement->user->username, 'achievement' => $userAchievement->achievement->name]
            )
        );

        return redirect()->route('lans.user-achievements.index', $lan);
    }
}
