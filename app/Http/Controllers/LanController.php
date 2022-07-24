<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Session;
use View;
use Zeropingheroes\Lanager\Achievement;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\Requests\StoreLanRequest;
use Zeropingheroes\Lanager\Venue;

class LanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $lans = Lan::orderBy('start', 'desc')
            ->get();

        // LAN happening now, or closest future LAN, or most recently ended past LAN
        $currentLan = Lan::happeningNow()->first()
            ?? Lan::future()->orderBy('start', 'asc')->first()
            ?? Lan::past()->orderBy('end', 'desc')->first();

        return View::make('pages.lans.index')
            ->with('lans', $lans)
            ->with('currentLan', $currentLan);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', Lan::class);

        return View::make('pages.lans.create')
            ->with('venues', Venue::orderBy('name')->get())
            ->with('achievements', Achievement::orderBy('name')->get())
            ->with('lan', new Lan());
    }

    /**
     * Display the specified resource.
     *
     * @param  Lan $lan
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function show(Lan $lan)
    {
        $this->authorize('view', $lan);

        return redirect()->route('lans.events.index', $lan);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $httpRequest
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(Request $httpRequest)
    {
        $this->authorize('create', Lan::class);

        $input = [
            'name' => $httpRequest->input('name'),
            'start' => $httpRequest->input('start'),
            'end' => $httpRequest->input('end'),
            'venue_id' => $httpRequest->input('venue_id'),
            'achievement_id' => $httpRequest->input('achievement_id'),
            'published' => $httpRequest->has('published'),
        ];

        $request = new StoreLanRequest($input);

        if ($request->invalid()) {
            Session::flash('error', $request->errors());

            return redirect()->back()->withInput();
        }

        $lan = Lan::create($input);

        return redirect()
            ->route('lans.show', $lan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Lan $lan
     * @return \Illuminate\Contracts\View\View
     * @throws AuthorizationException
     */
    public function edit(Lan $lan)
    {
        $this->authorize('update', $lan);

        return View::make('pages.lans.edit')
            ->with('venues', Venue::orderBy('name')->get())
            ->with('achievements', Achievement::orderBy('name')->get())
            ->with('lan', $lan);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $httpRequest
     * @param  Lan     $lan
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Request $httpRequest, Lan $lan)
    {
        $this->authorize('update', $lan);

        $input = [
            'name' => $httpRequest->input('name'),
            'start' => $httpRequest->input('start'),
            'end' => $httpRequest->input('end'),
            'venue_id' => $httpRequest->input('venue_id'),
            'achievement_id' => $httpRequest->input('achievement_id'),
            'published' => $httpRequest->has('published'),
            'id' => $lan->id,
        ];

        $request = new StoreLanRequest($input);

        if ($request->invalid()) {
            Session::flash('error', $request->errors());

            return redirect()->back()->withInput();
        }

        $lan->update($input);

        return redirect()
            ->route('lans.show', $lan);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Lan $lan
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Lan $lan)
    {
        $this->authorize('delete', $lan);

        Lan::destroy($lan->id);

        Session::flash(
            'success',
            trans('phrase.item-name-deleted', ['item' => trans('title.lan'), 'name' => $lan->name])
        );

        return redirect()->route('lans.index');
    }
}
