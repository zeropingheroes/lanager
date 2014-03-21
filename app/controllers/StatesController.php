<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\Models\State,
	Zeropingheroes\Lanager\Repositories\StateRepositoryInterface;
use View;

class StatesController extends BaseController {

	protected $states;
	
	public function __construct(StateRepositoryInterface $states)
	{
		$this->states = $states;
	}

	/**
	 * Display applications currently in use.
	 *
	 * @return Response
	 */
	public function currentApplicationUsage()
	{
		$applications = $this->states->getCurrentApplicationUsage();
		return View::make('state.usage')
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
		$servers = $this->states->getCurrentServerUsage();
		return View::make('state.usage')
					->with('title','Game Servers Currently Being Used')
					->with('itemsInUse',$servers);
	}

}