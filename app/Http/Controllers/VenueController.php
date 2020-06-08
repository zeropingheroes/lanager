<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Session;
use View;
use Zeropingheroes\Lanager\Requests\StoreVenueRequest;
use Zeropingheroes\Lanager\Venue;

class VenueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
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
     * @return \Illuminate\Contracts\View\View
     * @throws AuthorizationException
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
     * @param Request $httpRequest
     * @return RedirectResponse
     * @throws AuthorizationException
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
            Session::flash('error', $request->errors());
            return redirect()->back()->withInput();
        }

        $venue = Venue::create($input);

        return redirect()
            ->route('venues.show', $venue);
    }

    /**
     * Display the specified resource.
     *
     * @param Venue $venue
     * @return \Illuminate\Contracts\View\View
     * @throws AuthorizationException
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
     * @param Venue $venue
     * @return \Illuminate\Contracts\View\View
     * @throws AuthorizationException
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
     * @param Request $httpRequest
     * @param Venue $venue
     * @return RedirectResponse
     * @throws AuthorizationException
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
            Session::flash('error', $request->errors());
            return redirect()->back()->withInput();
        }

        $venue->update($input);

        return redirect()->route('venues.show', $venue);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Venue $venue
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Venue $venue)
    {
        $this->authorize('delete', $venue);

        Venue::destroy($venue->id);

        Session::flash(
            'success',
            trans('phrase.item-name-deleted', [
                'item' => trans('title.venue'),
                'name' => $venue->name
            ])
           );

        return redirect()->route('venues.index');
    }
}
