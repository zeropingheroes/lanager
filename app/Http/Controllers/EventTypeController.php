<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\EventType;
use Illuminate\Http\Request;

class EventTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View::make('pages.event-types.index')
            ->with('eventTypes', EventType::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View::make('pages.event-types.create')
            ->with('eventType', new EventType);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\EventType $eventType
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Zeropingheroes\Lanager\EventType  $eventType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EventType $eventType)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Zeropingheroes\Lanager\EventType  $eventType
     * @return \Illuminate\Http\Response
     */
    public function destroy(EventType $eventType)
    {
        //
    }
}
