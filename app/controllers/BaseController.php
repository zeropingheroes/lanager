<?php namespace Zeropingheroes\Lanager;

use Illuminate\Routing\Controller;
use Zeropingheroes\Lanager\ResourceServiceListenerContract,
	Zeropingheroes\Lanager\BaseResourceService,
	Zeropingheroes\Lanager\Api\v1\Traits\WriteableResourceTrait;
use Notification, Redirect;

class BaseController extends Controller implements ResourceServiceListenerContract {

	use WriteableResourceTrait;
	
	protected $service;

	public function __construct()
	{
		$this->beforeFilter( 'permission' );
	}

	/*
	|--------------------------------------------------------------------------
	| Default Controller Listener Methods
	|--------------------------------------------------------------------------
	|
	| These methods provide sensible boilerplate defaults for after-success and
	| after-failure actions when the app is being accessed via a web browser. 
	| These methods can be overridden by child controllers if needed.
	|
	*/
	/**
	 * Display HTML notification and redirect based on service action result
	 * @param  BaseResourceService $service Service class that was called
	 */
	public function storeSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.show', $service->resourceIds() );
	}

	/**
	 * @see BaseController::storeSucceeded
	 */
	public function storeFailed( BaseResourceService $service )
	{
		Notification::danger( $service->errors() );
		return Redirect::back()->withInput();
	}

	/**
	 * @see BaseController::storeSucceeded
	 */
	public function updateSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.show', $service->resourceIds() );
	}

	/**
	 * @see BaseController::storeSucceeded
	 */
	public function updateFailed( BaseResourceService $service )
	{
		Notification::danger( $service->errors() );
		return Redirect::back()->withInput();
	}

	/**
	 * @see BaseController::storeSucceeded
	 */
	public function destroySucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.index', $service->resourceIds() );
	}

	/**
	 * @see BaseController::storeSucceeded
	 */
	public function destroyFailed( BaseResourceService $service )
	{
		Notification::danger( $service->errors() );
		return Redirect::back();
	}

}