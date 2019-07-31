<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Zeropingheroes\Lanager\Lan;
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

//        dd($picks);

        return View::make('pages.lans.attendee-game-picks.index')
            ->with('lan', $lan)
            ->with('lanPicks', $lanPicks)
            ->with('userPicks', $userPicks);
    }

}
