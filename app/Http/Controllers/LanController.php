<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Throwable;
use Zeropingheroes\Lanager\Models\Achievement;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Models\Venue;
use Zeropingheroes\Lanager\Requests\StoreLanRequest;
use Zeropingheroes\Lanager\Services\CloneLanService;

class LanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): ViewContract
    {
        $lans = Lan::with('venue')
            ->orderBy('start', 'desc')
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
            'default_event_discord_notification_message' => $this->discardIfDefault($httpRequest->input('default_event_discord_notification_message')),
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
            'default_event_discord_notification_message' => $this->discardIfDefault($httpRequest->input('default_event_discord_notification_message')),
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
     * Publish the specified resource
     *
     * @throws AuthorizationException
     */
    public function publish(Lan $lan): RedirectResponse
    {
        $this->authorize('update', $lan);

        $input = [
            'name' => $lan->name,
            'start' => $lan->start->format('Y-m-d H:i'),
            'end' => $lan->end->format('Y-m-d H:i'),
            'venue_id' => $lan->venue_id,
            'achievement_id' => $lan->achievement_id,
            'published' => true,
            'default_event_discord_notification_message' => $lan->default_event_discord_notification_message,
            'id' => $lan->id,
        ];

        $storeLanRequest = new StoreLanRequest($input);

        if ($storeLanRequest->invalid()) {
            Session::flash('error', $storeLanRequest->errors());

            return redirect()->back();
        }

        $lan->update($input);

        return redirect()->back();
    }

    /**
     * Unpublish the specified resource.
     *
     * @throws AuthorizationException
     */
    public function unpublish(Lan $lan): RedirectResponse
    {
        $this->authorize('update', $lan);

        $lan->update(['published' => false]);

        return redirect()->back();
    }

    /**
     * The submitted default Discord notification message, or null if it is blank or matches
     * the system default message text.
     */
    private function discardIfDefault(?string $message): ?string
    {
        if (! $message) {
            return null;
        }

        return $message === trans('phrase.default-event-discord-notification-message') ? null : $message;
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

    /**
     * Show the form for cloning an existing LAN.
     *
     * @throws AuthorizationException
     */
    public function clone(Lan $lan): ViewContract
    {
        $this->authorize('create', Lan::class);

        $lan->loadMissing([
            'guides',
            'events',
            'slides',
            'discordChannelWebhooks',
        ]);

        return View::make('pages.lans.clone')
            ->with('venues', Venue::orderBy('name')->get())
            ->with('achievements', Achievement::orderBy('name')->get())
            ->with('sourceLan', $lan)
            ->with('lan', new Lan)
            ->with('guides', $lan->guides)
            ->with('events', $lan->events)
            ->with('slides', $lan->slides)
            ->with('webhooks', $lan->discordChannelWebhooks);
    }

    /**
     * Store a new LAN cloned from an existing one, along with the selected Guides, Events,
     * Slides and Discord channel webhooks.
     *
     * @throws AuthorizationException|Throwable
     */
    public function storeClone(Request $httpRequest, Lan $lan): RedirectResponse
    {
        $this->authorize('create', Lan::class);

        $input = [
            'name' => $httpRequest->input('name'),
            'start' => $httpRequest->input('start'),
            'end' => $httpRequest->input('end'),
            'venue_id' => $httpRequest->input('venue_id'),
            'achievement_id' => $httpRequest->input('achievement_id'),
            'published' => false,
            'default_event_discord_notification_message' => $this->discardIfDefault($httpRequest->input('default_event_discord_notification_message')),
        ];

        $storeLanRequest = new StoreLanRequest($input);

        if ($storeLanRequest->invalid()) {
            Session::flash('error', $storeLanRequest->errors());

            return redirect()->back()->withInput();
        }

        $newLan = (new CloneLanService)->clone(
            sourceLanId: $lan->id,
            name: $input['name'],
            venueId: $httpRequest->filled('venue_id') ? (int) $httpRequest->input('venue_id') : null,
            achievementId: $httpRequest->filled('achievement_id') ? (int) $httpRequest->input('achievement_id') : null,
            start: $input['start'],
            end: $input['end'],
            defaultEventDiscordNotificationMessage: $input['default_event_discord_notification_message'],
            guideIds: $httpRequest->input('guide_ids', []),
            eventIds: $httpRequest->input('event_ids', []),
            slideIds: $httpRequest->input('slide_ids', []),
            webhookIds: $httpRequest->input('webhook_ids', []),
        );

        Session::flash('success', trans('phrase.successfully-cloned-lan', ['sourceLanName' => $lan->name]));

        return redirect()->route('lans.events.index', $newLan);
    }
}
