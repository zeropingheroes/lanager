<?php namespace Zeropingheroes\Lanager;

use Notification;

class NotificationListener implements ResourceServiceListenerContract {

	public function storeSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
	}

	public function storeFailed( BaseResourceService $service )
	{
		Notification::danger( $service->errors() );
	}

	public function updateSucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
	}

	public function updateFailed( BaseResourceService $service )
	{
		Notification::danger( $service->errors() );
	}

	public function destroySucceeded( BaseResourceService $service )
	{
		Notification::success( $service->messages() );
	}

	public function destroyFailed( BaseResourceService $service )
	{
		Notification::danger( $service->errors() );
	}


}