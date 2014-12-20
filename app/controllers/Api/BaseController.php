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
	protected $resourceService;

	public function __construct()
	{
		$this->beforeFilter( 'permission' );
		$this->resourceService = new $this->resourceService($this); // for child controllers
		$this->resourceTransformer = new $this->resourceTransformer;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return $this->resourceService->all();
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return $this->resourceService->single($id);
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
	public function storeSucceeded( ResourceServiceContract $resourceService )
	{
		return $this->response->created();
	}

	public function storeFailed( ResourceServiceContract $resourceService )
	{
		throw new StoreResourceFailedException('Could not create new ' . $resourceService->resourceName, $resourceService->errors);
	}

	public function updateSucceeded( ResourceServiceContract $resourceService )
	{
		return $this->response->noContent();
	}

	public function updateFailed( ResourceServiceContract $resourceService )
	{
		throw new UpdateResourceFailedException('Could not update ' . $resourceService->resourceName, $resourceService->errors);
	}

	public function destroySucceeded( ResourceServiceContract $resourceService )
	{
		return $this->response->noContent();
	}

	public function destroyFailed( ResourceServiceContract $resourceService )
	{
		throw new DeleteResourceFailedException('Could not destroy ' . $resourceService->resourceName, $resourceService->errors);
	}

}