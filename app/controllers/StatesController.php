<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\States\State,
	Zeropingheroes\Lanager\States\StateContract;
use View;

class StatesController extends BaseController {

	protected $stateInterface;
	
	public function __construct(StateContract $stateInterface)
	{
		$this->stateInterface = $stateInterface;
	}

	/**
	 * Display applications currently in use.
	 *
	 * @return Response
	 */
	public function currentApplicationUsage()
	{
		$applications = $this->stateInterface->getCurrentApplicationUsage();
		return View::make('states.usage')
					->with('title','Games Currently Being Played')
					->with('itemsInUse',$applications);
	}

	/**
	 * Display servers currently in use.
	 *
	 * @return Response
	 */
	public function currentServerUsage()
	{
		$servers = $this->stateInterface->getCurrentServerUsage();
		return View::make('states.usage')
					->with('title','Game Servers Currently Being Used')
					->with('itemsInUse',$servers);
	}

}