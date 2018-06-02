<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Illuminate\Http\Request;
use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\Event as EventResource;
use Zeropingheroes\Lanager\Event;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $events = Event::visible();

        if ($request->filled('after')) {
            $events->where('start', '>', $request->after);
            $events->orWhere('end', '>', $request->after);
        }

        if ($request->filled('limit')) {
            $events->limit($request->limit);
        }

        $events = $events->orderBy('start')->get();

        return EventResource::collection($events);
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
