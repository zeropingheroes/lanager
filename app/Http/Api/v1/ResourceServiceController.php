<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Illuminate\Routing\Controller;
use Dingo\Api\Routing\ControllerTrait;
use	Dingo\Api\Exception\StoreResourceFailedException;
use	Dingo\Api\Exception\UpdateResourceFailedException;
use	Dingo\Api\Exception\DeleteResourceFailedException;
use Zeropingheroes\Lanager\Domain\ValidationException;
use DomainException;
use Input;

abstract class ResourceServiceController extends Controller {

	use ControllerTrait;

	/**
	 * Transformer class to use when presenting the resource data to the user
	 * @var object TransformerAbstract
	 */
	protected $transformer;

	/**
	 * Service class to use when working with the resource
	 * @var object ResourceService
	 */
	protected $service;

	/**
	 * Protect and filter all API controllers
	 */
	public function __construct()
	{
		$this->protect( ['store', 'update', 'destroy'] ); // require API auth for these methods
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		try
		{
			$this->service->store( Input::get() );
			return $this->response->created();
		}
		catch ( ValidationException $e )
		{
			throw new StoreResourceFailedException( 'Store failed', $e->getValidationErrors() );
		}
		catch ( DomainException $e )
		{
			throw new StoreResourceFailedException( 'Store failed', [ $e->getMessage() ] );
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @return Response
	 */
	public function update()
	{
		try
		{
			$ids = func_get_args();
			$id = end( $ids );
			$this->service->update( $id, Input::get() );
			return $this->response->noContent();
		}
		catch ( ValidationException $e )
		{
			throw new UpdateResourceFailedException( 'Update failed', $e->getValidationErrors() );
		}
		catch ( DomainException $e )
		{
			throw new UpdateResourceFailedException( 'Update failed', [ $e->getMessage() ] );
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @return Response
	 */
	public function destroy()
	{
		try
		{
			$ids = func_get_args();
			$id = end( $ids );
			$this->service->destroy( $id );
			return $this->response->noContent();
		}
		catch ( ValidationException $e )
		{
			throw new DeleteResourceFailedException( 'Delete failed', $e->getValidationErrors() );
		}
		catch ( DomainException $e )
		{
			throw new DeleteResourceFailedException( 'Delete failed', [ $e->getMessage() ] );
		}
	}

}