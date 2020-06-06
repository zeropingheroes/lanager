<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\LanGame;
use Zeropingheroes\Lanager\LanGameVote;
use Zeropingheroes\Lanager\Requests\StoreLanGameVoteRequest;
use Illuminate\Http\Request;

class LanGameVoteController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $httpRequest
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(Request $httpRequest)
    {
        $this->authorize('create', LanGameVote::class);

        $input = [
            'lan_game_id' => $httpRequest->input('lan_game_id'),
            'user_id' => Auth::user()->id,
        ];

        $request = new StoreLanGameVoteRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }

        $lanGameVote = LanGameVote::create($input);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Lan $lan
     * @param LanGame $lanGame
     * @param LanGameVote $vote
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Lan $lan, LanGame $lanGame, LanGameVote $vote)
    {
        $this->authorize('delete', $vote);

        LanGameVote::destroy($vote->id);

        return redirect()->back();
    }
}
