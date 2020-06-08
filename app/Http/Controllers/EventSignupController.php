<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Zeropingheroes\Lanager\Event;
use Zeropingheroes\Lanager\EventSignup;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\Requests\StoreEventSignupRequest;

class EventSignupController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Lan $lan
     * @param Event $event
     * @param Request $httpRequest
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(Lan $lan, Event $event, Request $httpRequest)
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

        $request = new StoreEventSignupRequest($input);

        if ($request->invalid()) {
            return redirect()
                ->back()
                ->withError($request->errors())
                ->withInput();
        }

        EventSignup::create($input);

        return redirect()
            ->route('lans.events.show', ['lan' => $lan, 'event' => $event]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Lan $lan
     * @param Event $event
     * @param EventSignup $eventSignup
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Lan $lan, Event $event, EventSignup $eventSignup)
    {
        $this->authorize('delete', $eventSignup);

        // If the event is accessed via the wrong LAN ID, show 404
        if ($event->lan_id != $lan->id || $eventSignup->event_id != $event->id) {
            abort(404);
        }

        EventSignup::destroy($eventSignup->id);

        return redirect()
            ->route('lans.events.show', ['lan' => $event->lan, 'event' => $event]);
    }
}
