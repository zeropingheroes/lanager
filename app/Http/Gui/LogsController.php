<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Zeropingheroes\Lanager\Domain\Logs\LogService;
use Input;
use View;

class LogsController extends ResourceServiceController {

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		$this->service = new logService;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if( Input::get( 'sapi' ) )
			$this->service->filterBySapi( Input::get( 'sapi' ) );

		if( Input::get( 'minLevel' ) )
			$this->service->filterByMinimumLevel( Input::get( 'minLevel' ) );

		$logs = $this->service->all();

		return View::make( 'logs.index' )
					->with( 'title', 'Logs' )
					->with( 'logs', $logs );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show( $id )
	{
		$log = $this->service->single( $id );

		return View::make( 'logs.show' )
					->with( 'title', 'Log Item ' . $log->id )
					->with( 'log', $log );
	}

}