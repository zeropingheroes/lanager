<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\States\StateContract;
use Zeropingheroes\Lanager\States\State;
use Carbon\Carbon;
use View, Input, App;

class UsageController extends BaseController {

	protected $timestamp;
	protected $stateInterface;
	
	public function __construct(StateContract $stateInterface)
	{
		$this->stateInterface = $stateInterface;
		
		if( (Input::get('timestamp') < time()) && Input::get('timestamp') != 0)
		{
			$this->timestamp = Carbon::createFromTimeStamp(Input::get('timestamp'));
		}
		else
		{
			$this->timestamp = Carbon::now();
		}
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

		$diffForHumans = ($this->timestamp->eq(Carbon::now())) ? 'Now' : $this->timestamp->diffForHumans();

		switch($resource)
		{
			case 'applications':
				$usage = $this->stateInterface->getApplicationUsage('', $this->timestamp->timestamp);
				$title = 'Games Being Played - ' . $diffForHumans;
				break;
			case 'servers':
				$usage = $this->stateInterface->getServerUsage('', $this->timestamp->timestamp);
				$title = 'Game Servers Being Used - ' . $diffForHumans;
				break;
		}
		$lastUpdated = new Carbon(State::max('created_at'));

		return View::make('usage.'.$resource)
					->with('title',$title)
					->with('usage',$usage)
					->with('lastUpdated',$lastUpdated);
	}

}
