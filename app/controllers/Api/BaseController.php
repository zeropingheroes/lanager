<?php namespace Zeropingheroes\Lanager\Api;

use Illuminate\Routing\Controller;
use Dingo\Api\Routing\ControllerTrait,
	Dingo\Api\Exception\StoreResourceFailedException,
	Dingo\Api\Exception\UpdateResourceFailedException,
	Dingo\Api\Exception\DeleteResourceFailedException;
use Zeropingheroes\Lanager\ResourceServiceListenerContract,
	Zeropingheroes\Lanager\ResourceServiceContract;
use Input;

class BaseController extends Controller implements ResourceServiceListenerContract {

	use ControllerTrait;

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
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		return $this->resourceService->store( Input::get() );
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

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		return $this->resourceService->update( $id, Input::get() );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		return $this->resourceService->destroy( $id );
	}

	/* Listeners */
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