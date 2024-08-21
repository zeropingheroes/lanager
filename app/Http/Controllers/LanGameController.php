<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\LanGame;
use Zeropingheroes\Lanager\Models\LanGameVote;
use Zeropingheroes\Lanager\Requests\StoreLanGameRequest;

class LanGameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Lan $lan): ViewContract
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
     * @throws AuthorizationException
     */
    public function store(Lan $lan, Request $httpRequest): RedirectResponse
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
     * @throws AuthorizationException
     */
    public function edit(Lan $lan, LanGame $lanGame): ViewContract
    {
        $this->authorize('update', $lanGame);

        return View::make('pages.lan-games.edit')
            ->with('lanGame', $lanGame);
    }

    /**
     * Update the specified resource in storage.
     * @throws AuthorizationException
     */
    public function update(Request $httpRequest, Lan $lan, LanGame $lanGame): RedirectResponse
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
     * @throws AuthorizationException
     */
    public function destroy(Lan $lan, LanGame $lanGame): RedirectResponse
    {
        $this->authorize('delete', $lanGame);

        LanGame::destroy($lanGame->id);

        Session::flash(
            'success',
            trans('phrase.item-name-deleted', ['item' => trans('title.game'), 'name' => $lanGame->game_name])
        );

        return redirect()->route('lans.lan-games.index', ['lan' => $lanGame->lan]);
    }
}
