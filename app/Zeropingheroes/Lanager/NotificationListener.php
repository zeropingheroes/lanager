<?php namespace Zeropingheroes\Lanager;

use Notification;

class NotificationListener implements ResourceServiceListenerContract {

	/**
	 * Display HTML notification based on service action result
	 * @param  BaseResourceService $service Service class that was called
	 */
	public function storeSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
	}

	/**
	 * @see NotificationListener::storeSucceeded
	 */
	public function storeFailed( BaseResourceService $service )
	{
		Notification::danger( $service->errors() );
	}

	/**
	 * @see NotificationListener::storeSucceeded
	 */
	public function updateSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
	}

	/**
	 * @see NotificationListener::storeSucceeded
	 */
	public function updateFailed( BaseResourceService $service )
	{
		Notification::danger( $service->errors() );
	}

	/**
	 * @see NotificationListener::storeSucceeded
	 */
	public function destroySucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
	}

	/**
	 * @see NotificationListener::storeSucceeded
	 */
	public function destroyFailed( BaseResourceService $service )
	{
		Notification::danger( $service->errors() );
	}

}