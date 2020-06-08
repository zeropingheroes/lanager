<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use View;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\LanGame;
use Zeropingheroes\Lanager\LanGameVote;
use Zeropingheroes\Lanager\Requests\StoreLanGameRequest;

class LanGameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Lan $lan)
    {
        $lanGames = $lan->games()
            ->withCount('votes')
            ->orderBy('votes_count', 'desc')
            ->orderBy('game_name', 'asc')
            ->get();
        return View::make('pages.lan-games.index')
            ->with('lan', $lan)
            ->with('lanGames', $lanGames);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $httpRequest
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(Lan $lan, Request $httpRequest)
    {
        $this->authorize('create', LanGame::class);

        $input = [
            'lan_id' => $lan->id,
            'game_name' => $httpRequest->input('game_name'),
            'created_by' => Auth::user()->id,
        ];

        $request = new StoreLanGameRequest($input);

        if ($request->invalid()) {
            Session::flash('error', $request->errors());
            return redirect()->back()->withInput();
        }

        LanGame::create($input)->votes()->save(new LanGameVote(['user_id' => Auth::user()->id]));

        return redirect()->route('lans.lan-games.index', ['lan' => $lan]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param LanGame $lanGame
     * @return \Illuminate\Contracts\View\View
     * @throws AuthorizationException
     */
    public function edit(Lan $lan, LanGame $lanGame)
    {
        $this->authorize('update', $lanGame);

        return View::make('pages.lan-games.edit')
            ->with('lanGame', $lanGame);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $httpRequest
     * @param Lan $lan
     * @param LanGame $lanGame
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Request $httpRequest, Lan $lan, LanGame $lanGame)
    {
        $this->authorize('update', $lanGame);

        $input = [
            'lan_id' => $lanGame->lan->id,
            'game_name' => $httpRequest->input('game_name'),
            'id' => $lanGame->id,
        ];

        $request = new StoreLanGameRequest($input);

        if ($request->invalid()) {
            Session::flash('error', $request->errors());
            return redirect()->back()->withInput();
        }

        $lanGame->update($input);

        return redirect()->route('lans.lan-games.index', ['lan' => $lanGame->lan]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Lan $lan
     * @param LanGame $lanGame
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy(Lan $lan, LanGame $lanGame)
    {
        $this->authorize('delete', $lanGame);

        LanGame::destroy($lanGame->id);

        Session::flash(
            'success',
            __('phrase.item-name-deleted', ['item' => __('title.game'), 'name' => $lanGame->game_name])
           );

        return redirect()->route('lans.lan-games.index', ['lan' => $lanGame->lan]);
    }
}
