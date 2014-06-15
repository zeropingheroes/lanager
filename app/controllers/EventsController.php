<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Models\Event,
	Zeropingheroes\Lanager\Models\EventType;
use View, Input, Redirect, Request, Response, URL, Auth;

class EventsController extends BaseController {

	
	public function __construct()
	{
		// Check if user can access requested method
		$this->beforeFilter('checkResourcePermission',array('only' => array('create', 'store', 'edit', 'update', 'destroy') ));
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
		return View::make('events.timetable')
					->with('title','Events Timetable');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$events = Event::orderBy('start')->get();
		
		return View::make('events.index')
					->with('title','Events List')
					->with('events',$events);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$eventTypes = array('' => ' ') + EventType::lists('name','id');
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
		$event->name 			= Input::get('name');
		$event->description 	= Input::get('description');
		$event->start 			= Input::get('start');
		$event->end 			= Input::get('end');
		$event->signup_opens	= (Input::get('signup_opens') != NULL ? Input::get('signup_opens') : NULL);
		$event->signup_closes	= (Input::get('signup_closes') != NULL ? Input::get('signup_closes') : NULL);
		$event->event_type_id 	= (is_numeric(Input::get('event_type_id')) ? Input::get('event_type_id') : NULL); // turn non-numeric & empty values into NULL

		return $this->process( $event );		

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$event = Event::find($id);

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
		$event = Event::find($id);
		$eventTypes = array('' => ' ') + EventType::lists('name','id');

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
		$event = Event::find($id);
		$event->name 			= Input::get('name');
		$event->description		= Input::get('description');
		$event->start 			= Input::get('start');
		$event->end 			= Input::get('end');
		$event->signup_opens	= (Input::get('signup_opens') != NULL ? Input::get('signup_opens') : NULL);
		$event->signup_closes	= (Input::get('signup_closes') != NULL ? Input::get('signup_closes') : NULL);
		$event->event_type_id	= (is_numeric(Input::get('event_type_id')) ? Input::get('event_type_id') : NULL); // turn non-numeric & empty values into NULL
		
		return $this->process( $event );		

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

		return $this->process( $event );
	}

	public function join($id)
	{
		$event = Event::find($id);
		if( !$event->users->contains(Auth::user()) AND (strtotime($event->signup_closes) > time()) ) 
		{
			$event->users()->attach(Auth::user());
		}
		return Redirect::route('events.show',array('event' => $event->id));
	}

	public function leave($id)
	{
		$event = Event::find($id);
		if( $event->users->contains(Auth::user()) )
		{
			$event->users()->detach(Auth::user());
		}
		return Redirect::route('events.show',array('event' => $event->id));
	}


}