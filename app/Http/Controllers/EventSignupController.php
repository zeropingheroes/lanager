<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Zeropingheroes\Lanager\Models\Event;
use Zeropingheroes\Lanager\Models\EventSignup;
use Zeropingheroes\Lanager\Models\Lan;
use Zeropingheroes\Lanager\Requests\StoreEventSignupRequest;

class EventSignupController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @throws AuthorizationException
     */
    public function store(Lan $lan, Event $event, Request $httpRequest): RedirectResponse
    {
        $this->authorize('create', [EventSignup::class, $event]);

        // If the event is accessed via the wrong LAN ID, show 404
        if ($event->lan_id != $lan->id) {
            abort(404);
        }

        $input = [
            'event_id' => $event->id,
            'user_id' => $httpRequest->input('user_id') ?? Auth::user()->id,
        ];

        $storeEventSignupRequest = new StoreEventSignupRequest($input);

        if ($storeEventSignupRequest->invalid()) {
            Session::flash('error', $storeEventSignupRequest->errors());

            return redirect()->back()->withInput();
        }

        EventSignup::create($input);

        return redirect()
            ->route('lans.events.show', ['lan' => $lan, 'event' => $event]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @throws AuthorizationException
     */
    public function destroy(Lan $lan, Event $event, EventSignup $signup): RedirectResponse
    {
        $this->authorize('delete', $signup);

        // If the event is accessed via the wrong LAN ID, show 404
        if ($event->lan_id != $lan->id || $signup->event_id != $event->id) {
            abort(404);
        }

        EventSignup::destroy($signup->id);

        return redirect()
            ->route('lans.events.show', ['lan' => $event->lan, 'event' => $event]);
    }
}
