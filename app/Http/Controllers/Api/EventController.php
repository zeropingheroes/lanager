<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Http\Controllers\Api;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Zeropingheroes\Lanager\Http\Controllers\Controller;
use Zeropingheroes\Lanager\Http\Resources\EventResource;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\Lan;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Lan $lan): AnonymousResourceCollection
    {
        if (! $lan->published) {
            abort(404);
        }

        $events = Event::where('lan_id', $lan->id)
            ->where('published', true)
            ->orderBy('start')
            ->get();

        return EventResource::collection($events);
    }

    /**
     * Display the specified resource.
     */
    public function show(Lan $lan, Event $event): EventResource
    {
        if ($event->lan_id !== $lan->id) {
            abort(404);
        }

        if (! $event->published || ! $event->lan->published) {
            abort(404);
        }

        return new EventResource($event);
    }
}
