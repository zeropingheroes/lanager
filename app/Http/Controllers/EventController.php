<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Event;
use Illuminate\Http\Request;
use Zeropingheroes\Lanager\EventType;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\Requests\StoreEventRequest;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return View::make('pages.events.index')
            ->with('events', Event::visible()->orderBy('start')->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return View::make('pages.events.create')
            ->with('lans', Lan::orderBy('start')->get())
            ->with('eventTypes', EventType::orderBy('name')->get())
            ->with('event', new Event);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $httpRequest
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $httpRequest)
    {
        $this->authorize('create', Event::class);

        $input = [
            'lan_id' => $httpRequest->input('lan_id'),
            'name' => $httpRequest->input('name'),
            'description' => $httpRequest->input('description'),
            'start' => $httpRequest->input('start'),
            'end' => $httpRequest->input('end'),
            'event_type_id' => $httpRequest->input('event_type_id'),
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
            ->route('events.index')
            ->withSuccess(__('phrase.event-successfully-created', ['name' => $event->name]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\Event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(Event $event)
    {
        $event = Event::visible()->findOrFail($event->id);

        return View::make('pages.events.show')
            ->with('event', $event);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\Event $event
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Event $event)
    {
        return View::make('pages.events.edit')
            ->with('lans', Lan::orderBy('start')->get())
            ->with('eventTypes', EventType::orderBy('name')->get())
            ->with('event', $event);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $httpRequest
     * @param  \Zeropingheroes\Lanager\Event $event
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $httpRequest, Event $event)
    {
        $this->authorize('update', Event::class);

        $input = [
            'lan_id' => $httpRequest->input('lan_id'),
            'name' => $httpRequest->input('name'),
            'description' => $httpRequest->input('description'),
            'start' => $httpRequest->input('start'),
            'end' => $httpRequest->input('end'),
            'event_type_id' => $httpRequest->input('event_type_id'),
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
            ->route('events.index')
            ->withSuccess(__('phrase.event-successfully-updated', ['name' => $event->name]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Zeropingheroes\Lanager\Event $event
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        Event::destroy($event->id);

        return redirect()
            ->route('events.index')
            ->withSuccess(__('phrase.event-successfully-deleted', ['name' => $event->name]));
    }
}
