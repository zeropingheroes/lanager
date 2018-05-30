<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\Event as EventResource;
use Zeropingheroes\Lanager\Event;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return EventResource::collection(Event::visible()->orderBy('start')->get());
    }

    /**
     * Display the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\Event $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        $event->load('lan');
        return new EventResource($event);
    }
}
