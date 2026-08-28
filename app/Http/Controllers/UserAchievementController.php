<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Models\Achievement;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\UserAchievement;
use Zeropingheroes\Lanager\Requests\StoreUserAchievementRequest;

class UserAchievementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @throws AuthorizationException
     */
    public function index(Lan $lan): ViewContract
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
     * @throws AuthorizationException
     */
    public function store(Request $httpRequest, Lan $lan): RedirectResponse
    {
        $this->authorize('create', UserAchievement::class);

        $input = $httpRequest->only(['user_id', 'achievement_id']);
        $input['lan_id'] = $lan->id;

        $storeUserAchievementRequest = new StoreUserAchievementRequest($input);

        if ($storeUserAchievementRequest->invalid()) {
            Session::flash('error', $storeUserAchievementRequest->errors());

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
     * @throws AuthorizationException
     */
    public function destroy(Lan $lan, UserAchievement $userAchievement): RedirectResponse
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
