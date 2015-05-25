<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\ApplicationUsage\ApplicationUsageService;
use Illuminate\Routing\Controller;
use Dingo\Api\Routing\ControllerTrait;

class ApplicationUsageController extends Controller {

	use ControllerTrait;
	
	/**
	 * Service class used to get data
	 * @var object BaseResourceService
	 */
	protected $service;


	/**
	 * Set the service classe
	 */
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