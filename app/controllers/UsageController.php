<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\States\StateContract;
use Zeropingheroes\Lanager\States\State;
use View, Response, Input, App, ExpressiveDate, Request;

class UsageController extends BaseController {

	protected $timestamp;
	protected $stateInterface;
	
	public function __construct(StateContract $stateInterface)
	{
		$this->stateInterface = $stateInterface;
		$this->timestamp = ((Input::get('timestamp') < time()) && Input::get('timestamp') != 0) ? Input::get('timestamp') : time();
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
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

		$usageTime = new ExpressiveDate();
		$usageTimeGrammar = ($this->timestamp == time()) ? 'Now' : $usageTime->setTimestamp($this->timestamp)->getRelativeDate();

		switch($resource)
		{
			case 'applications':
				$usage = $this->stateInterface->getApplicationUsage('', $this->timestamp);
				$title = 'Games Being Played - '.$usageTimeGrammar;
				break;
			case 'servers':
				$usage = $this->stateInterface->getServerUsage('', $this->timestamp);
				$title = 'Game Servers Being Used - '.$usageTimeGrammar;
				break;
		}
		$lastUpdated = new ExpressiveDate(State::max('created_at'));

		if ( Request::ajax() ) return Response::json($usage);

		return View::make('usage.'.$resource)
					->with('title',$title)
					->with('usage',$usage)
					->with('lastUpdated',$lastUpdated);
	}

}
