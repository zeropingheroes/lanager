<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Http\Request;
use Zeropingheroes\Lanager\Requests\DestroyUserAchievementRequest;
use Zeropingheroes\Lanager\UserAchievement;
use Zeropingheroes\Lanager\User;
use Illuminate\Support\Facades\View;

class UserAchievementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $this->authorize('index', UserAchievement::class);

        $userAchievements = UserAchievement::with('user', 'achievement')->get();
        $users = User::orderBy('username')->get();
        $achievements = Achievement::all();

        return View::make('pages.user-achievements.index')
            ->with('userAchievements', $userAchievements)
            ->with('users', $users)
            ->with('achievements', $achievements);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $httpRequest
     * @return \Illuminate\Http\Response
     * @internal param Request|StoreUserAchievementRequest $request
     */
    public function store(Request $httpRequest)
    {
        $this->authorize('create', UserAchievement::class);

        $input = $httpRequest->only(['user_id', 'achievement_id']);

        $request = new StoreUserAchievementRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }

        $userAchievement = UserAchievement::create($input);

        return redirect()
            ->route('user-achievements.index')
            ->withSuccess(
                __(
                    'phrase.achievement-successfully-awarded',
                    ['user' => $userAchievement->user->username, 'achievement' => $userAchievement->achievement->name]
                )
            );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Zeropingheroes\Lanager\UserAchievement $userAchievement
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserAchievement $userAchievement)
    {
        $this->authorize('delete', $userAchievement);

        UserAchievement::destroy($userAchievement->id);

        return redirect()
            ->route('user-achievements.index')
            ->withSuccess(
                __(
                    'phrase.achievement-successfully-revoked',
                    ['user' => $userAchievement->user->username, 'achievement' => $userAchievement->achievement->name]
                )
            );
    }
}
