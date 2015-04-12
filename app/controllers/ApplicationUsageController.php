<?php namespace Zeropingheroes\Lanager;

use Zeropingheroes\Lanager\ApplicationUsage\ApplicationUsageService;
use View;

class ApplicationUsageController extends BaseController {

	protected $applicationUsage;

	/**
	 * Set the controller's service
	 */
	public function __construct()
	{
		$this->applicationUsage = new ApplicationUsageService;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$applications = $this->applicationUsage->userTotalsAt( time() );

		return View::make('application-usage.index')
					->with('title', 'Games Being Played')
					->with('applications', $applications);
	}

}
