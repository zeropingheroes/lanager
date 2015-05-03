<?php namespace Zeropingheroes\Lanager\Http;

use Input;

trait WriteableResourceTrait {

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		// Flat resource
		if( func_num_args() == 0 ) return $this->service->store( Input::get() );
		
		// Nested resource
		if( func_num_args() > 0 ) return $this->service->store( func_get_args(), Input::get() );
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update()
	{
		// Flat resource
		if( func_num_args() == 1 ) return $this->service->update( func_get_arg(0), Input::get() );
		
		// Nested resource
		if( func_num_args() > 1 ) return $this->service->update( func_get_args(), Input::get() );
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy()
	{
		// Flat resource
		if( func_num_args() == 1 ) return $this->service->destroy( func_get_arg(0) );
		// Nested resource
		if( func_num_args() > 1 ) return $this->service->destroy( func_get_args() );
	}

}