<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use View;
use Zeropingheroes\Lanager\Achievement;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\Requests\StoreUserAchievementRequest;
use Zeropingheroes\Lanager\UserAchievement;

class UserAchievementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Lan $lan
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
     * @param Lan $lan
     * @return Response
     * @throws AuthorizationException
     */
    public function store(Request $httpRequest, Lan $lan)
    {
        $this->authorize('create', UserAchievement::class);

        $input = $httpRequest->only(['user_id', 'achievement_id']);
        $input['lan_id'] = $lan->id;

        $request = new StoreUserAchievementRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }

        $userAchievement = UserAchievement::create($input);

        return redirect()
            ->route('lans.user-achievements.index', $lan)
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
     * @param Lan $lan
     * @param UserAchievement $userAchievement
     * @return Response
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

        return redirect()
            ->route('lans.user-achievements.index', $lan)
            ->withSuccess(
                __(
                    'phrase.achievement-successfully-revoked',
                    ['user' => $userAchievement->user->username, 'achievement' => $userAchievement->achievement->name]
                )
            );
    }
}
