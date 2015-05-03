<?php namespace Zeropingheroes\Lanager\Domain;

interface ResourceServiceListenerContract {

	public function storeSucceeded( BaseResourceService $resourceService );

	public function storeFailed( BaseResourceService $resourceService );

	public function updateSucceeded( BaseResourceService $resourceService );

	public function updateFailed( BaseResourceService $resourceService );

	public function destroySucceeded( BaseResourceService $resourceService );

	public function destroyFailed( BaseResourceService $resourceService );

}