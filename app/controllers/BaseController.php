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
	public function storeSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.show', $service->resourceIds() );
	}

	public function storeFailed( BaseResourceService $service )
	{
		Notification::danger( $service->errors() );
		return Redirect::back()->withInput();
	}

	public function updateSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.show', $service->resourceIds() );
	}

	public function updateFailed( BaseResourceService $service )
	{
		Notification::danger( $service->errors() );
		return Redirect::back()->withInput();
	}

	public function destroySucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.index', $service->resourceIds() );
	}

	public function destroyFailed( BaseResourceService $service )
	{
		Notification::danger( $service->errors() );
		return Redirect::back();
	}

}