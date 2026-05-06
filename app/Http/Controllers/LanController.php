<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Models\Achievement;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Venue;
use Zeropingheroes\Lanager\Requests\StoreLanRequest;

class LanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ViewContract
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
     * @throws AuthorizationException
     */
    public function create(): ViewContract
    {
        $this->authorize('create', Lan::class);

        return View::make('pages.lans.create')
            ->with('venues', Venue::orderBy('name')->get())
            ->with('achievements', Achievement::orderBy('name')->get())
            ->with('lan', new Lan);
    }

    /**
     * Display the specified resource.
     *
     * @throws AuthorizationException
     */
    public function show(Lan $lan): RedirectResponse
    {
        $this->authorize('view', $lan);

        return redirect()->route('lans.events.index', $lan);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws AuthorizationException
     */
    public function store(Request $httpRequest): RedirectResponse
    {
        $this->authorize('create', Lan::class);

        $input = [
            'name' => $httpRequest->input('name'),
            'start' => $httpRequest->input('start'),
            'end' => $httpRequest->input('end'),
            'venue_id' => $httpRequest->input('venue_id'),
            'achievement_id' => $httpRequest->input('achievement_id'),
            'published' => $httpRequest->has('published'),
            'discord_webhook_url' => $httpRequest->input('discord_webhook_url') ?: null,
        ];

        $storeLanRequest = new StoreLanRequest($input);

        if ($storeLanRequest->invalid()) {
            Session::flash('error', $storeLanRequest->errors());

            return redirect()->back()->withInput();
        }

        $lan = Lan::create($input);

        return redirect()
            ->route('lans.show', $lan);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @throws AuthorizationException
     */
    public function edit(Lan $lan): ViewContract
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
     * @throws AuthorizationException
     */
    public function update(Request $httpRequest, Lan $lan): RedirectResponse
    {
        $this->authorize('update', $lan);

        $input = [
            'name' => $httpRequest->input('name'),
            'start' => $httpRequest->input('start'),
            'end' => $httpRequest->input('end'),
            'venue_id' => $httpRequest->input('venue_id'),
            'achievement_id' => $httpRequest->input('achievement_id'),
            'published' => $httpRequest->has('published'),
            'discord_webhook_url' => $httpRequest->input('discord_webhook_url') ?: null,
            'id' => $lan->id,
        ];

        $storeLanRequest = new StoreLanRequest($input);

        if ($storeLanRequest->invalid()) {
            Session::flash('error', $storeLanRequest->errors());

            return redirect()->back()->withInput();
        }

        $lan->update($input);

        return redirect()
            ->route('lans.show', $lan);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws AuthorizationException
     */
    public function destroy(Lan $lan): RedirectResponse
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
