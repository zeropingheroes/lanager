<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Zeropingheroes\Lanager\LanGameVote;
use Zeropingheroes\Lanager\Requests\StoreLanGameRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class LanGameVoteController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $httpRequest
     * @return \Illuminate\Http\Response
     */
//    public function store(Request $httpRequest)
//    {
//        $this->authorize('create', LanGame::class);
//
//        $input = [
//            'name' => $httpRequest->input('name'),
//            'description' => $httpRequest->input('description'),
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
//        $lanGame = LanGame::create($input);
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
