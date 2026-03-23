<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\LanGame;
use Zeropingheroes\Lanager\Models\LanGameVote;
use Zeropingheroes\Lanager\Requests\StoreLanGameVoteRequest;

class LanGameVoteController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @throws AuthorizationException
     */
    public function store(Request $httpRequest): RedirectResponse
    {
        $this->authorize('create', LanGameVote::class);

        $input = [
            'lan_game_id' => $httpRequest->input('lan_game_id'),
            'user_id' => Auth::user()->id,
        ];

        $storeLanGameVoteRequest = new StoreLanGameVoteRequest($input);

        if ($storeLanGameVoteRequest->invalid()) {
            Session::flash('error', $storeLanGameVoteRequest->errors());

            return redirect()->back()->withInput();
        }

        LanGameVote::create($input);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws AuthorizationException
     */
    public function destroy(Lan $lan, LanGame $lanGame, LanGameVote $lanGameVote): RedirectResponse
    {
        $this->authorize('delete', $lanGameVote);

        LanGameVote::destroy($lanGameVote->id);

        return redirect()->back();
    }
}
