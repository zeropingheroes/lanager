<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\EventTypes\EventTypeService;
use View, Notification, Redirect;

class EventTypesController extends BaseController {

	protected $route = 'event-types';

	public function __construct()
	{
		parent::__construct();
		$this->service = new EventTypeService($this);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$eagerLoad = ['events'];
		$eventTypes = $this->service->all( [], $eagerLoad );

		return View::make('event-types.index')
					->with('title','Event Types')
					->with('eventTypes', $eventTypes );
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('event-types.create')
					->with('title','Create Event Type')
					->with('eventType',null);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return View::make('event-types.edit')
					->with('title','Edit Event Type')
					->with('eventType',$this->service->single($id));
	}

	public function storeSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.index', $service->resourceIds() );
	}

	public function updateSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.index', $service->resourceIds() );
	}

}