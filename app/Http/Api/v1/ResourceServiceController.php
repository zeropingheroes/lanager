<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Illuminate\Routing\Controller;
use Dingo\Api\Routing\ControllerTrait;
use	Dingo\Api\Exception\StoreResourceFailedException;
use	Dingo\Api\Exception\UpdateResourceFailedException;
use	Dingo\Api\Exception\DeleteResourceFailedException;
use Zeropingheroes\Lanager\Domain\ResourceServiceListenerContract;
use	Zeropingheroes\Lanager\Domain\BaseResourceService;
use Zeropingheroes\Lanager\Http\Api\v1\Traits\ReadableResourceTrait;
use	Zeropingheroes\Lanager\Http\WriteableResourceTrait;

abstract class ResourceServiceController extends Controller implements ResourceServiceListenerContract {

	use ControllerTrait;
	use ReadableResourceTrait;
	use WriteableResourceTrait;

	/**
	 * Transformer class to use when presenting the resource data to the user
	 * @var object TransformerAbstract
	 */
	protected $transformer;

	/**
	 * Service class to use when working with the resource
	 * @var object BaseResourceService
	 */
	protected $service;

	/**
	 * Whether the resource can be marked as a draft and hidden from standard users
	 * @var object BaseResourceService
	 */
	protected $draftable = false;

	/**
	 * Protect and filter all API controllers
	 */
	public function __construct()
	{
		$this->protect(['store', 'update', 'destroy']); // require API auth for these methods
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


	/**
	 * Display JSON notification based on service action result
	 * @param  BaseResourceService $service Service class that was called
	 */
	public function storeSucceeded( BaseResourceService $service )
	{
		return $this->response->created();
	}

	/**
	 * @see ResourceServiceController::storeSucceeded
	 */
	public function storeFailed( BaseResourceService $service )
	{
		throw new StoreResourceFailedException('Could not create ' . trans('resources.' . $service->resource()), $service->errors() );
	}

	/**
	 * @see ResourceServiceController::storeSucceeded
	 */
	public function updateSucceeded( BaseResourceService $service )
	{
		return $this->response->noContent();
	}

	/**
	 * @see ResourceServiceController::storeSucceeded
	 */
	public function updateFailed( BaseResourceService $service )
	{
		throw new UpdateResourceFailedException('Could not update ' . trans('resources.' . $service->resource()), $service->errors() );
	}

	/**
	 * @see ResourceServiceController::storeSucceeded
	 */
	public function destroySucceeded( BaseResourceService $service )
	{
		return $this->response->noContent();
	}

	/**
	 * @see ResourceServiceController::storeSucceeded
	 */
	public function destroyFailed( BaseResourceService $service )
	{
		throw new DeleteResourceFailedException('Could not destroy ' . trans('resources.' . $service->resource()), $service->errors() );
	}

}