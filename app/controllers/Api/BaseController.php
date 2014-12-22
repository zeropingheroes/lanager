<?php namespace Zeropingheroes\Lanager\Api;

use Illuminate\Routing\Controller;
use Dingo\Api\Routing\ControllerTrait,
	Dingo\Api\Exception\StoreResourceFailedException,
	Dingo\Api\Exception\UpdateResourceFailedException,
	Dingo\Api\Exception\DeleteResourceFailedException;
use Zeropingheroes\Lanager\ResourceServiceListenerContract,
	Zeropingheroes\Lanager\ResourceServiceContract,
	Zeropingheroes\Lanager\ResourceControllerTrait;
use Input;

class BaseController extends Controller implements ResourceServiceListenerContract {

	use ControllerTrait;
	use ResourceControllerTrait;

	protected $resourceTransformer;
	protected $service;

	public function __construct()
	{
		$this->beforeFilter( 'permission' );
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return $this->service->all();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return $this->service->single($id);
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
	public function storeSucceeded( ResourceServiceContract $service )
	{
		return $this->response->created();
	}

	public function storeFailed( ResourceServiceContract $service )
	{
		throw new StoreResourceFailedException('Could not create new ' . $service->resourceName, $service->errors);
	}

	public function updateSucceeded( ResourceServiceContract $service )
	{
		return $this->response->noContent();
	}

	public function updateFailed( ResourceServiceContract $service )
	{
		throw new UpdateResourceFailedException('Could not update ' . $service->resourceName, $service->errors);
	}

	public function destroySucceeded( ResourceServiceContract $service )
	{
		return $this->response->noContent();
	}

	public function destroyFailed( ResourceServiceContract $service )
	{
		throw new DeleteResourceFailedException('Could not destroy ' . $service->resourceName, $service->errors);
	}

}