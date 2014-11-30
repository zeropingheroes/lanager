<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Events\Event,
	Zeropingheroes\Lanager\Events\Types\Type,
	Zeropingheroes\Lanager\Events\EventValidator;
use View, Input, Redirect, Request, Response, URL, Auth, Notification;

class EventsController extends BaseController {

	
	public function __construct()
	{
		$this->beforeFilter('permission', ['only' => ['create', 'store', 'edit', 'update', 'destroy'] ]);
	}

	/**
	 * Display the events in a timetable.
	 *
	 * @return Response
	 */
	public function timetable()
	{
		if (Request::ajax()) {
			$events = Event::all();
			
			//format JSON for FullCalendar
			foreach($events as $event)
			{
				$fullCalendarJson[] = array(
					'start'		=> date('c',strtotime($event->start)),
					'end'		=> date('c',strtotime($event->end)),
					'title'		=> $event->name,
					'allDay'	=> false,
					'url'		=> URL::route('events.show', $event->id),
					'color'		=> (isset($event->event_type->colour) ? $event->event_type->colour : ''),
					);
			}
			return Response::json($fullCalendarJson);
		}
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$events = Event::orderBy('start')->with('type')->get();
	
		return View::make('events.index')
					->with('title','Events')
					->with('events',$events);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$eventTypes = array('' => ' ') + Type::lists('name','id');
		$event = new Event;
		return View::make('events.create')
					->with('title','Create Event')
					->with('eventTypes',$eventTypes)
					->with('event',$event);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$event = new Event;

		if( Input::has('name') )			$event->name 			= Input::get('name');
		if( Input::has('description') )		$event->description 	= Input::get('description');
		if( Input::has('start') )			$event->start 			= Input::get('start');
		if( Input::has('end') )				$event->end 			= Input::get('end');
		if( Input::has('signup_opens') )	$event->signup_opens 	= Input::get('signup_opens');
		if( Input::has('signup_closes') )	$event->signup_closes 	= Input::get('signup_closes');
		if( Input::has('event_type_id') )	$event->event_type_id 	= Input::get('event_type_id');

		$eventValidator = EventValidator::make( $event->toArray() )->scope('store');

		if ( $eventValidator->fails() )
		{
			Notification::danger( $eventValidator->errors()->all() );
			return Redirect::back()->withInput();
		}

		$event->save();
		Notification::success('Event successfully stored');
		return Redirect::route('events.show', $event->id);	

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$event = Event::findOrFail($id);

		return View::make('events.show')
					->with('title',$event->name)
					->with('event',$event);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$event = Event::findOrFail($id);
		$eventTypes = array('' => ' ') + Type::lists('name','id');

		return View::make('events.edit')
					->with('title','Edit Event')
					->with('eventTypes',$eventTypes)
					->with('event',$event);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$event = Event::findOrFail($id);

		if( Input::has('name') )			$event->name 			= Input::get('name');
		if( Input::has('description') )		$event->description 	= Input::get('description');
		if( Input::has('start') )			$event->start 			= Input::get('start');
		if( Input::has('end') )				$event->end 			= Input::get('end');
		if( Input::has('signup_opens') )	$event->signup_opens 	= Input::get('signup_opens');
		if( Input::has('signup_closes') )	$event->signup_closes 	= Input::get('signup_closes');
		if( Input::has('event_type_id') )	$event->event_type_id 	= Input::get('event_type_id');
		
		$eventValidator = EventValidator::make( $event->toArray() )->scope('update');

		if ( $eventValidator->fails() )
		{
			Notification::danger( $eventValidator->errors()->all() );
			return Redirect::back()->withInput();
		}

		$event->save();
		Notification::success('Event successfully updated');
		return Redirect::route('events.show', $event->id);	
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$event = Event::findOrFail($id);

		$event->delete();
		Notification::success('Event successfully destroyed');
		return Redirect::route('events.index');
	}

}