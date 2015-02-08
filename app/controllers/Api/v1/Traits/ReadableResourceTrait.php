<?php namespace Zeropingheroes\Lanager\Api\v1\Traits;

trait ReadableResourceTrait {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$items = $this->service->all( func_get_args() );

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
		$item = $this->service->single( func_get_args() );

		return $this->response->item( $item, $this->transformer );
	}

}