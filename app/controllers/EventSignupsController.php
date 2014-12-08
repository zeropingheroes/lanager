<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\EventSignups\EventSignup,
	Zeropingheroes\Lanager\Events\Event,
	Zeropingheroes\Lanager\Users\User;
use View, Input, Redirect, Authority, Auth, Notification;

class EventSignupsController extends BaseController {
	
	public function __construct()
	{
		$this->beforeFilter('permission', ['only' =>  ['store', 'destroy'] ]);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($eventId)
	{
		$event = Event::findOrFail($eventId);
		$eventSignups = EventSignup::where('event_id', $eventId)->with( ['user'] )->get();

		return View::make('event-signups.index')
					->with('title', $event->name . ' - Signups')
					->with('event',$event)
					->with('eventSignups',$eventSignups);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($eventId)
	{
		$event = Event::findOrFail($eventId);
		
		// TODO: move business logic to service
		if( (Input::get('user_id') != Auth::user()->id) && Authority::cannot('manage', 'events.signups') )
		{
			Notification::danger('You are not permitted to sign up other users to an event');
			return Redirect::back()->withInput();			
		}
		if ( ! $event->isOpenForSignups() )
		{
			Notification::danger('This event is not open for signups');
			return Redirect::back()->withInput();
		}

		$eventSignup = new EventSignup;
		
		$eventSignup->fill( Input::get() );
		$eventSignup->event_id = $event->id;

		if ( ! $this->save($eventSignup) ) return Redirect::back()->withInput();
		
		return Redirect::route('events.signups.show', $eventSignup->event_id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($eventId, $eventSignupId)
	{
		$eventSignup = EventSignup::findOrFail($eventSignupId);

		if ( Authority::cannot('delete', 'events.signups', $eventSignup) ) return App::abort(403);

		$this->delete($eventSignup);
		return Redirect::route('events.signups.show', $eventId);
	}

}