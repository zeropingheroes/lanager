<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Models\Venue;
use Zeropingheroes\Lanager\Requests\StoreVenueRequest;

class VenueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ViewContract
    {
        $venues = Venue::all();

        return View::make('pages.venues.index')
            ->with('venues', $venues);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     */
    public function create(): ViewContract
    {
        $this->authorize('create', Venue::class);

        return View::make('pages.venues.create')
            ->with('venue', new Venue);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws AuthorizationException
     */
    public function store(Request $httpRequest): RedirectResponse
    {
        $this->authorize('create', Venue::class);

        $input = [
            'name' => $httpRequest->input('name'),
            'street_address' => $httpRequest->input('street_address'),
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
     * @throws AuthorizationException
     */
    public function show(Venue $venue): ViewContract
    {
        $this->authorize('view', $venue);

        return View::make('pages.venues.show')
            ->with('venue', $venue);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @throws AuthorizationException
     */
    public function edit(Venue $venue): ViewContract
    {
        $this->authorize('update', $venue);

        return View::make('pages.venues.edit')
            ->with('venue', $venue);
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws AuthorizationException
     */
    public function update(Request $httpRequest, Venue $venue): RedirectResponse
    {
        $this->authorize('update', $venue);

        $input = [
            'name' => $httpRequest->input('name'),
            'street_address' => $httpRequest->input('street_address'),
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
     * @throws AuthorizationException
     */
    public function destroy(Venue $venue): RedirectResponse
    {
        $this->authorize('delete', $venue);

        Venue::destroy($venue->id);

        Session::flash(
            'success',
            trans(
                'phrase.item-name-deleted',
                [
                    'item' => trans('title.venue'),
                    'name' => $venue->name,
                ]
            )
        );

        return redirect()->route('venues.index');
    }
}
