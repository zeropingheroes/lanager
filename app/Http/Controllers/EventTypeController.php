<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\EventType;
use Illuminate\Http\Request;
use Zeropingheroes\Lanager\Requests\StoreEventTypeRequest;

class EventTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return View::make('pages.event-types.index')
            ->with('eventTypes', EventType::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $this->authorize('create', EventType::class);

        return View::make('pages.event-types.create')
            ->with('eventType', new EventType);
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
        $this->authorize('create', EventType::class);

        $input = [
            'name' => $httpRequest->input('name'),
            'colour' => $httpRequest->input('colour'),
        ];

        $request = new StoreEventTypeRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }
        $eventType = EventType::create($input);

        return redirect()
            ->route('event-types.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param EventType $eventType
     * @return \Illuminate\Contracts\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(EventType $eventType)
    {
        $this->authorize('update', $eventType);

        return View::make('pages.event-types.edit')
            ->with('eventType', $eventType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $httpRequest
     * @param EventType $eventType
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $httpRequest, EventType $eventType)
    {
        $this->authorize('update', $eventType);

        $input = [
            'name' => $httpRequest->input('name'),
            'colour' => $httpRequest->input('colour'),
        ];

        $request = new StoreEventTypeRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }
        $eventType->update($input);

        return redirect()
            ->route('event-types.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  EventType $eventType
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(EventType $eventType)
    {
        $this->authorize('delete', $eventType);

        EventType::destroy($eventType->id);

        return redirect()
            ->route('event-types.index')
            ->withSuccess(__('phrase.item-name-deleted', ['item' => __('title.event-type'), 'name' => $eventType->name]));

    }
}
