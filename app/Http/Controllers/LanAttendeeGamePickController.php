<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\LanAttendeeGamePick;
use Zeropingheroes\Lanager\Requests\StoreLanAttendeeGamePickRequest;
use Zeropingheroes\Lanager\Services\GetLanAttendeeGamePicksService;

class LanAttendeeGamePickController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Lan $lan
     * @return \Illuminate\Contracts\View\View
     * @internal param Request $request
     */
    public function index(Lan $lan)
    {
        $games = (new GetLanAttendeeGamePicksService($lan))->get();

        return View::make('pages.lans.attendee-game-picks.index')
            ->with('lan', $lan)
            ->with('games', $games);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Lan $lan
     * @param Request $httpRequest
     * @return \Illuminate\Http\Response
     */
    public function store(Lan $lan, Request $httpRequest)
    {
        $this->authorize('create', LanAttendeeGamePick::class);

        $input = [
            'game_id' => $httpRequest->input('game_id'),
            'game_id_type' => $httpRequest->input('game_id_type'),
            'lan_id' => $lan->id,
            'user_id' => Auth::user()->id,
        ];

        $request = new StoreLanAttendeeGamePickRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }
        LanAttendeeGamePick::create($input);

        return redirect()->route('lans.attendee-game-picks.index', $lan);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Zeropingheroes\Lanager\Lan $lan
     * @param LanAttendeeGamePick $lanAttendeeGamePick
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lan $lan, $lanAttendeeGamePick)
    {
        // TODO: Find out why type-hinting in the method isn't working (we have to do this instead)
        $lanAttendeeGamePick = LanAttendeeGamePick::findOrFail($lanAttendeeGamePick);

        $this->authorize('delete', $lanAttendeeGamePick);

        if ($lanAttendeeGamePick->lan_id != $lan->id) {
            abort(404);
        }

        LanAttendeeGamePick::destroy($lanAttendeeGamePick->id);

        return redirect()->route('lans.attendee-game-picks.index', $lan);
    }
}
