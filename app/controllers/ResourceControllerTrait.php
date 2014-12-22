<?php namespace Zeropingheroes\Lanager;

use Input;

trait ResourceControllerTrait {

	/*
	|--------------------------------------------------------------------------
	| Default Controller Methods
	|--------------------------------------------------------------------------
	|
	| These methods provide sensible boilerplate defaults for processing and
	| responding to requests. These methods can be overridden in child controllers
	| if needed.
	|
	*/

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		return $this->service->store( Input::get() );
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		return $this->service->update( $id, Input::get() );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		return $this->service->destroy( $id );
	}

}