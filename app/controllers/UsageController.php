<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\States\StateContract;
use View, Response, Input, App;

class UsageController extends BaseController {

	protected $timestamp;
	protected $stateInterface;
	
	public function __construct(StateContract $stateInterface)
	{
		$this->stateInterface = $stateInterface;
		$this->timestamp = Input::get('timestamp', time());
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return 'all types of usage at '.$this->timestamp;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  str  $resource
	 * @return Response
	 */
	public function show($resource)
	{
		if( !in_array($resource, array('applications', 'servers'))) App::abort(404);

		switch($resource)
		{
			case 'applications':
				$usage = $this->stateInterface->getCurrentApplicationUsage();
				$title = 'Games Currently Being Played';
				break;
			case 'servers':
				$usage = $this->stateInterface->getCurrentServerUsage();
				$title = 'Game Servers Currently Being Used';
				break;
		}

		return View::make('usage.'.$resource)
					->with('title',$title)
					->with($resource,$usage);
	}

}