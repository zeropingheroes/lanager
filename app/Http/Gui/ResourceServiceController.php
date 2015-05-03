<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Illuminate\Routing\Controller;
use Zeropingheroes\Lanager\Http\WriteableResourceTrait;
use Zeropingheroes\Lanager\Domain\ResourceServiceListenerContract;
use Zeropingheroes\Lanager\Domain\BaseResourceService;
use Notification;
use Redirect;

abstract class ResourceServiceController extends Controller implements ResourceServiceListenerContract {

	use WriteableResourceTrait;

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
	 * @see ResourceServiceController::storeSucceeded
	 */
	public function storeFailed( BaseResourceService $service )
	{
		Notification::danger( $service->errors() );
		return Redirect::back()->withInput();
	}

	/**
	 * @see ResourceServiceController::storeSucceeded
	 */
	public function updateSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.show', $service->resourceIds() );
	}

	/**
	 * @see ResourceServiceController::storeSucceeded
	 */
	public function updateFailed( BaseResourceService $service )
	{
		Notification::danger( $service->errors() );
		return Redirect::back()->withInput();
	}

	/**
	 * @see ResourceServiceController::storeSucceeded
	 */
	public function destroySucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
		return Redirect::route( $this->route . '.index', $service->resourceIds() );
	}

	/**
	 * @see ResourceServiceController::storeSucceeded
	 */
	public function destroyFailed( BaseResourceService $service )
	{
		Notification::danger( $service->errors() );
		return Redirect::back();
	}

}