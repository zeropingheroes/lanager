<?php namespace Zeropingheroes\Lanager\Api\v1;

use Zeropingheroes\Lanager\ApplicationUsage\ApplicationUsageService;
use Illuminate\Routing\Controller;
use Dingo\Api\Routing\ControllerTrait;


class ApplicationUsageController extends Controller {

	protected $service;

	use ControllerTrait;

	public function __construct()
	{
		$this->service = new ApplicationUsageService;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$applicationsInUse = $this->service->userTotalsAt( time() );

		return $this->response->array( $applicationsInUse );
	}

}