<?php namespace Zeropingheroes\Lanager\Http\Api\v1\Traits;

use Input;
use Authority;
use App;

trait ReadableResourceTrait {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if( $this->draftable == true )
		{
			if( Authority::cannot( 'manage', $this->service->resource() ) ) $this->service->where('published', true);
		}
		
		// Flat resource
		if( func_num_args() == 0 ) $items = $this->service->all( Input::all() );
		
		// Nested resource
		if( func_num_args() > 0 ) $items = $this->service->all( func_get_args(), Input::all() );

		return $this->response->collection( $items, $this->transformer );
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show()
	{
		// Flat resource
		if( func_num_args() == 1 ) $item = $this->service->single( func_get_arg(0) );
		
		// Nested resource
		if( func_num_args() > 1 ) $item = $this->service->single( func_get_args() );

		if( $this->draftable == true )
		{
			if( ! $item->published AND Authority::cannot( 'manage', $this->service->resource() ) )
			{
				throw new \Illuminate\Database\Eloquent\ModelNotFoundException;
			}
		}

		return $this->response->item( $item, $this->transformer );
	}

}