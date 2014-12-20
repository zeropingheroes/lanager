<?php namespace Zeropingheroes\Lanager;

interface ResourceServiceListenerContract {

	public function storeSucceeded( ResourceServiceContract $resourceService );

	public function storeFailed( ResourceServiceContract $resourceService );

	public function updateSucceeded( ResourceServiceContract $resourceService );

	public function updateFailed( ResourceServiceContract $resourceService );

	public function destroySucceeded( ResourceServiceContract $resourceService );

	public function destroyFailed( ResourceServiceContract $resourceService );

}