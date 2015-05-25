<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Zeropingheroes\Lanager\Domain\EventTypes\EventTypeService;
use View;
use Redirect;

class EventTypesController extends ResourceServiceController {

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		$this->service = new EventTypeService;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$eventTypes = $this->service->all();

		return View::make( 'event-types.index' )
					->with( 'title', 'Event Types' )
					->with( 'eventTypes', $eventTypes );
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make( 'event-types.create' )
					->with( 'title', 'Create Event Type' )
					->with( 'eventType',null);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$eventType = $this->service->single($id);

		return View::make( 'event-types.edit' )
					->with( 'title', 'Edit Event Type' )
					->with( 'eventType', $eventType);
	}

	protected function redirectAfterStore()
	{
		return Redirect::route('event-types.index', $this->currentRouteParameters() );
	}

	protected function redirectAfterUpdate()
	{
		return $this->redirectAfterStore();
	}

	protected function redirectAfterDestroy()
	{
		return $this->redirectAfterStore();
	}
}