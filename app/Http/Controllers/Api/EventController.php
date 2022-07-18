<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Zeropingheroes\Lanager\Event;
use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\Event as EventResource;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $events = Event::where('published', true)
            ->whereHas('lan', function ($query) {
                $query->where('published', true);
            });

        if ($request->filled('after')) {
            $events->where(
                function ($query) use ($request) {
                    $query->where('start', '>', $request->after)
                        ->orWhere('end', '>', $request->after);
                }
            );
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
     * @param  Event $event
     * @return EventResource
     */
    public function show(Event $event)
    {
        if (!$event->published || !$event->lan->published) {
            abort(404);
        }

        return new EventResource($event);
    }
}
