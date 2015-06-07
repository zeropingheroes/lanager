<?php namespace Zeropingheroes\Lanager\Http\Gui;

use Illuminate\Routing\Controller;
use Zeropingheroes\Lanager\Domain\ValidationException;
use DomainException;
use Input;
use Notification;
use Redirect;
use Request;
use Route;

abstract class ResourceServiceController extends Controller {

	/**
	 * Store a newly created resource in storage.
	 * 
	 * @return Response
	 */
	public function store()
	{
		return $this->processStore( Input::get() );
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param integer $id      Resource's ID
	 * @return Response
	 */
	public function update()
	{
		$id = func_get_arg(0);
		return $this->processUpdate( $id, Input::get() );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param integer $id      Resource's ID
	 * @return Response
	 */
	public function destroy()
	{
		$id = func_get_arg(0);
		return $this->processDestroy( $id );
	}

	/**
	 * Store a newly created resource in storage
	 * or handle any errors that may occur
	 * 
	 * @param array   $input   Resource's data
	 * @return Response
	 */
	protected function processStore( $input )
	{
		try
		{
			$this->service->store( $input );
			return $this->redirectAfterStore();
		}
		catch ( ValidationException $e )
		{
			Notification::danger( $e->getValidationErrors() );
			return Redirect::back()->withInput();
		}
		catch ( DomainException $e )
		{
			Notification::danger( $e->getMessage() );
			return Redirect::back()->withInput();
		}
	}

	/**
	 * Update the specified resource in storage
	 * or handle any errors that may occur
	 *
	 * @param integer $id      Resource's ID
	 * @param array   $input   Resource's data
	 * @return Response
	 */
	protected function processUpdate( $id, $input )
	{
		try
		{
			$this->service->update( $id, $input );
			return $this->redirectAfterUpdate();
		}
		catch ( ValidationException $e )
		{
			Notification::danger( $e->getValidationErrors() );
			return Redirect::back()->withInput();
		}
		catch ( DomainException $e )
		{
			Notification::danger( $e->getMessage() );
			return Redirect::back()->withInput();
		}
	}

	/**
	 * Remove the specified resource from storage
	 * or handle any errors that may occur
	 *
	 * @param integer $id      Resource's ID
	 * @return Response
	 */
	protected function processDestroy( $id )
	{
		try
		{
			$this->service->destroy( $id );
			return $this->redirectAfterDestroy();
		}
		catch ( ValidationException $e )
		{
			Notification::danger( $e->getValidationErrors() );
			return Redirect::back()->withInput();
		}
		catch ( DomainException $e )
		{
			Notification::danger( $e->getMessage() );
			return Redirect::back()->withInput();
		}
	}

	/**
	 * Generate response for redirect after successful store
	 * @return Response
	 */
	protected function redirectAfterStore()
	{
		return Redirect::to('/');
	}

	/**
	 * Generate response for redirect after successful update
	 * @return Response
	 */
	protected function redirectAfterUpdate()
	{
		return Redirect::to('/');
	}

	/**
	 * Generate response for redirect after successful destroy
	 * @return Response
	 */
	protected function redirectAfterDestroy()
	{
		return Redirect::to('/');
	}

	/**
	 * Get the current route parameters
	 * @return array
	 */
	protected function currentRouteParameters()
	{
		return Route::getCurrentRoute()->bindParameters( Request::instance() );
	}

}