<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Event;
use Illuminate\Http\Request;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\Requests\StoreEventRequest;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Lan $lan
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request, Lan $lan)
    {
        if ($request->has('schedule')) {
            return View::make('pages.events.schedule')
                ->with('lan', $lan);
        }

        $events = $lan->events()
            ->orderBy('start')
            ->get();

        return View::make('pages.events.index')
            ->with('lan', $lan)
            ->with('events', $events);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Lan $lan
     * @return \Illuminate\Contracts\View\View
     */
    public function create(Lan $lan)
    {
        $this->authorize('create', Event::class);

        return View::make('pages.events.create')
            ->with('lan', $lan)
            ->with('event', new Event);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $httpRequest
     * @param Lan $lan
     * @return \Illuminate\Http\Response
     */
    public function store(Request $httpRequest, Lan $lan)
    {
        $this->authorize('create', Event::class);

        $input = [
            'lan_id' => $httpRequest->input('lan_id') ?? $lan->id,
            'name' => $httpRequest->input('name'),
            'description' => $httpRequest->input('description'),
            'start' => $httpRequest->input('start'),
            'end' => $httpRequest->input('end'),
            'signups_open' => $httpRequest->input('signups_open'),
            'signups_close' => $httpRequest->input('signups_close'),
            'published' => $httpRequest->has('published'),
        ];

        $request = new StoreEventRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }
        $event = Event::create($input);

        return redirect()
            ->route('lans.events.show', ['lan' => $lan, 'event' => $event]);
    }

    /**
     * Display the specified resource.
     *
     * @param Lan $lan
     * @param Event $event
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Lan $lan, Event $event)
    {
        $this->authorize('view', $event);

        // If the event is accessed via the wrong LAN ID, show 404
        if ($event->lan_id != $lan->id) {
            abort(404);
        }

        return View::make('pages.events.show')
            ->with('lan', $lan)
            ->with('event', $event);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Lan $lan
     * @param Event $event
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Lan $lan, Event $event)
    {
        $this->authorize('update', $event);

        // If the event is accessed via the wrong LAN ID, show 404
        if ($event->lan_id != $lan->id) {
            abort(404);
        }

        return View::make('pages.events.edit')
            ->with('lan', $lan)
            ->with('event', $event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $httpRequest
     * @param Lan $lan
     * @param Event $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $httpRequest, Lan $lan, Event $event)
    {
        $this->authorize('update', $event);

        $input = [
            'lan_id' => $lan->id,
            'name' => $httpRequest->input('name'),
            'description' => $httpRequest->input('description'),
            'start' => $httpRequest->input('start'),
            'end' => $httpRequest->input('end'),
            'signups_open' => $httpRequest->input('signups_open'),
            'signups_close' => $httpRequest->input('signups_close'),
            'published' => $httpRequest->has('published'),
        ];

        $request = new StoreEventRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }
        $event->update($input);

        return redirect()
            ->route('lans.events.show', ['lan' => $lan, 'event' => $event]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Lan $lan
     * @param Event $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lan $lan, Event $event)
    {
        $this->authorize('delete', $event);

        Event::destroy($event->id);

        return redirect()
            ->route('lans.events.index', ['lan' => $lan])
            ->withSuccess(__('phrase.item-name-deleted', ['item' => __('title.event'), 'name' => $event->name]));
    }
}
