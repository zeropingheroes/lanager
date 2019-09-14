<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\Requests\StoreLanAttendeeGamePickRequest;
use Zeropingheroes\Lanager\LanAttendeeGamePick;
use Zeropingheroes\Lanager\Http\Resources\LanAttendeeGamePick as LanAttendeeGamePickResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LanAttendeeGamePickController extends Controller
{
    /**
     * LanAttendeeGamePickController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['only' => ['index', 'store', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Lan $lan
     * @return Game
     */
    public function index(Lan $lan)
    {
        $picks = $lan->attendeeGamePicks()
            ->orderBy('created_at', 'desc')
            ->get();

        return LanAttendeeGamePickResource::collection($picks);
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
            return response(['errors' => $request->errors()], 400);
        }
        $gamePick = LanAttendeeGamePick::create($input);

        if($gamePick->exists) {
            return response(null, 201);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Zeropingheroes\Lanager\Lan $lan
     * @param LanAttendeeGamePick $gamePick
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lan $lan, LanAttendeeGamePick $gamePick)
    {
        $this->authorize('delete', $gamePick);

        // If the game pick is accessed via the wrong LAN ID, show 404
        if ($gamePick->lan->id != $lan->id) {
            abort(404);
        }

        $destroyed = LanAttendeeGamePick::destroy($gamePick->id);

        if($destroyed) {
            return response(null, 200);
        }
    }
}
