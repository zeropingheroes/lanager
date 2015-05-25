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
		try
		{
			$this->service->store( Input::get() );
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