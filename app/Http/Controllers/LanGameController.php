<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\LanGame;
use Zeropingheroes\Lanager\Requests\StoreLanGameRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class LanGameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Lan $lan)
    {
        $lanGames = $lan->games()->withCount('votes')->orderBy('votes_count', 'desc')->get();
        return View::make('pages.lan-games.index')
            ->with('lan', $lan)
            ->with('lanGames', $lanGames);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $httpRequest
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
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
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }

        $lanGame = LanGame::create($input);

        return redirect()->route('lans.lan-games.index', ['lan' => $lan]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $httpRequest
     * @param  \Zeropingheroes\Lanager\LanGame $lanGame
     * @return \Illuminate\Http\Response
     */
//    public function update(Request $httpRequest, LanGame $lanGame)
//    {
//        $this->authorize('update', $lanGame);
//
//        $input = [
//            'lan_id' => $lan->id,
//            'game_name' => $httpRequest->input('game_name'),
//            'id' => $lanGame->id,
//        ];
//
//        $request = new StoreLanGameRequest($input);
//
//        if ($request->invalid()) {
//            return redirect()
//                ->back()
//                ->withError($request->errors())
//                ->withInput();
//        }
//
//        $lanGame->update($input);
//
//        return redirect()
//            ->route('lan-games.show', $lanGame);
//    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Zeropingheroes\Lanager\LanGame $lanGame
     * @return \Illuminate\Http\Response
     */
//    public function destroy(LanGame $lanGame)
//    {
//        $this->authorize('delete', $lanGame);
//
//        LanGame::destroy($lanGame->id);
//
//        return redirect()
//            ->route('lan-games.index')
//            ->withSuccess(__('phrase.item-name-deleted', [
//                'item' => __('title.lan-game'),
//                'name' => $lanGame->name
//            ]));
//    }
}
