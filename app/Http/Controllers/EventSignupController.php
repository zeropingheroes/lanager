<?php

namespace Zeropingheroes\Lanager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zeropingheroes\Lanager\Event;
use Zeropingheroes\Lanager\Lan;
use Zeropingheroes\Lanager\Requests\StoreEventSignupRequest;
use Zeropingheroes\Lanager\Requests\DestroyEventSignupRequest;
use Zeropingheroes\Lanager\EventSignup;

class EventSignupController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Lan $lan
     * @param Event $event
     * @param Request $httpRequest
     * @return \Illuminate\Http\Response
     * @internal param Request|StoreEventSignupRequest $request
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
     * @return \Illuminate\Http\Response
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
