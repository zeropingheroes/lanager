<?php namespace Zeropingheroes\Lanager\Http\Api\v1\Traits;

trait FlatResourceTrait
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $items = $this->service->all();

        return $this->response->collection($items, $this->transformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $item = $this->service->single($id);

        return $this->response->item($item, $this->transformer);
    }

}