<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\EventDiscordNotificationMessage;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Requests\StoreEventRequest;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Lan $lan): ViewContract
    {
        $this->authorize('view', $lan);

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
     * @throws AuthorizationException
     */
    public function create(Lan $lan): ViewContract
    {
        $this->authorize('create', Event::class);

        return View::make('pages.events.create')
            ->with('lan', $lan)
            ->with('event', new Event);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @throws AuthorizationException
     */
    public function store(Request $httpRequest, Lan $lan): RedirectResponse
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

        $storeEventRequest = new StoreEventRequest($input);

        if ($storeEventRequest->invalid()) {
            Session::flash('error', $storeEventRequest->errors());

            return redirect()->back()->withInput();
        }

        $event = DB::transaction(function () use ($httpRequest, $input): Event {
            $event = Event::create($input);

            if (
                $httpRequest->has('create_default_discord_notification_message')
                && $httpRequest->user()->can('update', EventDiscordNotificationMessage::class)
            ) {
                EventDiscordNotificationMessage::create([
                    'event_id' => $event->id,
                    'message' => null,
                ]);
            }

            return $event;
        });

        return redirect()->route('lans.events.show', ['lan' => $lan, 'event' => $event]);
    }

    /**
     * Display the specified resource.
     *
     * @throws AuthorizationException
     */
    public function show(Lan $lan, Event $event): ViewContract
    {
        $this->authorize('view', $event);

        // If the event is accessed via the wrong LAN ID, show 404
        if ($event->lan_id != $lan->id) {
            abort(404);
        }

        $event->loadMissing('discordNotificationMessage.images');
        $lan->loadMissing('discordChannelWebhooks');

        return View::make('pages.events.show')
            ->with('lan', $lan)
            ->with('event', $event);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @throws AuthorizationException
     */
    public function edit(Lan $lan, Event $event): ViewContract
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
     * @throws AuthorizationException
     */
    public function update(Request $httpRequest, Lan $lan, Event $event): RedirectResponse
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

        $storeEventRequest = new StoreEventRequest($input);

        if ($storeEventRequest->invalid()) {
            Session::flash('error', $storeEventRequest->errors());

            return redirect()->back()->withInput();
        }

        $event->update($input);

        return redirect()
            ->route('lans.events.show', ['lan' => $lan, 'event' => $event]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws AuthorizationException
     */
    public function destroy(Lan $lan, Event $event): RedirectResponse
    {
        $this->authorize('delete', $event);

        Event::destroy($event->id);

        Session::flash(
            'success',
            trans('phrase.item-name-deleted', ['item' => trans('title.event'), 'name' => $event->name])
        );

        return redirect()->route('lans.events.index', ['lan' => $lan]);
    }
}
