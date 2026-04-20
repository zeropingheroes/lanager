<?php

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\EventResource;
use Zeropingheroes\Lanager\Models\Event;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $events = Event::where('published', true)
            ->whereHas('lan', function ($query): void {
                $query->where('published', true);
            });

        if ($request->filled('after')) {
            $events->where(
                function ($query) use ($request): void {
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
     */
    public function show(Event $event): EventResource
    {
        if (! $event->published || ! $event->lan->published) {
            abort(404);
        }

        return new EventResource($event);
    }
}
