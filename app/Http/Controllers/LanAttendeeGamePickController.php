<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\LanAttendeeGamePick;
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
        $lanPicks = (new GetLanAttendeeGamePicksService($lan))->get();

        if(Auth::user()) {
            $userPicks = Auth::user()->gamePicks()->where('lan_id', $lan->id)->get();
        } else {
            $userPicks = collect();
        }

        return View::make('pages.lans.attendee-game-picks.index')
            ->with('lan', $lan)
            ->with('lanPicks', $lanPicks)
            ->with('userPicks', $userPicks);
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
