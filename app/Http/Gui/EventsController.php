<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Zeropingheroes\Lanager\Domain\Events\EventService;
use Zeropingheroes\Lanager\Domain\EventTypes\EventTypeService;
use Zeropingheroes\Lanager\Domain\EventSignups\EventSignupService;
use View;
use Redirect;

class EventsController extends ResourceServiceController {

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		$this->service = new EventService;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$events = $this->service->all();

		return View::make( 'events.index' )
					->with( 'title', 'Events' )
					->with( 'events', $events );
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$eventTypes = ['' => ''] + lists( (new EventTypeService)->all(), 'id', 'name' );

		return View::make( 'events.create' )
					->with( 'title', 'Create Event' )
					->with( 'eventTypes', $eventTypes )
					->with( 'event', null);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$event = $this->service->single($id);

		return View::make( 'events.show' )
					->with( 'title', $event->name )
					->with( 'event', $event );
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$event = $this->service->single($id);

		$eventTypes = ['' => ''] + lists( (new EventTypeService)->all(), 'id', 'name' );

		return View::make( 'events.edit' )
					->with( 'title', 'Edit Event' )
					->with( 'eventTypes', $eventTypes )
					->with( 'event', $event );
	}

	protected function redirectAfterStore()
	{
		return Redirect::route('events.show', $this->service->id() );
	}

	protected function redirectAfterUpdate()
	{
		return $this->redirectAfterStore();
	}

	protected function redirectAfterDestroy()
	{
		return Redirect::route('events.index');
	}

}