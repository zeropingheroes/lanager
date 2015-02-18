<?php namespace Zeropingheroes\Lanager\Api\v1;

use Illuminate\Routing\Controller;
use Dingo\Api\Routing\ControllerTrait,
	Dingo\Api\Exception\StoreResourceFailedException,
	Dingo\Api\Exception\UpdateResourceFailedException,
	Dingo\Api\Exception\DeleteResourceFailedException;
use Zeropingheroes\Lanager\ResourceServiceListenerContract,
	Zeropingheroes\Lanager\BaseResourceService;
use Zeropingheroes\Lanager\Api\v1\Traits\ReadableResourceTrait,
	Zeropingheroes\Lanager\Api\v1\Traits\WriteableResourceTrait;

class BaseController extends Controller implements ResourceServiceListenerContract {

	use ControllerTrait;
	use ReadableResourceTrait;
	use WriteableResourceTrait;

	protected $transformer;
	protected $service;

	public function __construct()
	{
		$this->protect(['store', 'update', 'destroy']); // require API auth for these methods
		$this->beforeFilter( 'permission' ); // for all methods, check if the user requesting has permission
	}

	/*
	|--------------------------------------------------------------------------
	| Default API Controller Listener Methods
	|--------------------------------------------------------------------------
	|
	| These methods provide sensible boilerplate defaults for after-success and
	| after-failure actions when the app is being accessed via REST API. 
	| These methods can be overridden by child controllers if needed.
	|
	*/
	public function storeSucceeded( BaseResourceService $service )
	{
		return $this->response->created();
	}

	public function storeFailed( BaseResourceService $service )
	{
		throw new StoreResourceFailedException('Could not create ' . trans('resources.' . $service->resource()), $service->errors() );
	}

	public function updateSucceeded( BaseResourceService $service )
	{
		return $this->response->noContent();
	}

	public function updateFailed( BaseResourceService $service )
	{
		throw new UpdateResourceFailedException('Could not update ' . trans('resources.' . $service->resource()), $service->errors() );
	}

	public function destroySucceeded( BaseResourceService $service )
	{
		return $this->response->noContent();
	}

	public function destroyFailed( BaseResourceService $service )
	{
		throw new DeleteResourceFailedException('Could not destroy ' . trans('resources.' . $service->resource()), $service->errors() );
	}

}