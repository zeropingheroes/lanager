<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Zeropingheroes\Lanager\Venue;
use Zeropingheroes\Lanager\Requests\StoreVenueRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class VenueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $venues = Venue::all();
        return View::make('pages.venues.index')
            ->with('venues', $venues);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Venue::class);

        return View::make('pages.venues.create')
            ->with('venue', new Venue);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $httpRequest
     * @return \Illuminate\Http\Response
     */
    public function store(Request $httpRequest)
    {
        $this->authorize('create', Venue::class);

        $input = [
            'name' => $httpRequest->input('name'),
            'street_address' => $httpRequest->input('street_address'),
            'description' => $httpRequest->input('description'),
        ];

        $request = new StoreVenueRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }

        $venue = Venue::create($input);

        return redirect()
            ->route('venues.show', $venue);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\Venue $venue
     * @return \Illuminate\Http\Response
     */
    public function show(Venue $venue)
    {
        $this->authorize('view', $venue);

        return View::make('pages.venues.show')
            ->with('venue', $venue);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Zeropingheroes\Lanager\Venue $venue
     * @return \Illuminate\Http\Response
     */
    public function edit(Venue $venue)
    {
        $this->authorize('update', $venue);

        return View::make('pages.venues.edit')
            ->with('venue', $venue);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $httpRequest
     * @param  \Zeropingheroes\Lanager\Venue $venue
     * @return \Illuminate\Http\Response
     */
    public function update(Request $httpRequest, Venue $venue)
    {
        $this->authorize('update', $venue);

        $input = [
            'name' => $httpRequest->input('name'),
            'street_address' => $httpRequest->input('street_address'),
            'description' => $httpRequest->input('description'),
            'id' => $venue->id,
        ];

        $request = new StoreVenueRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }

        $venue->update($input);

        return redirect()
            ->route('venues.show', $venue);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Zeropingheroes\Lanager\Venue $venue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venue $venue)
    {
        $this->authorize('delete', $venue);

        Venue::destroy($venue->id);

        return redirect()
            ->route('venues.index')
            ->withSuccess(__('phrase.item-name-deleted', [
                'item' => __('title.venue'),
                'name' => $venue->name
            ]));
    }
}
