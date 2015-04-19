<?php namespace Zeropingheroes\Lanager;

abstract class FlatResourceService extends BaseResourceService {

	/**
	 * The resource's model that the service will use
	 * @var object BaseModel
	 */
	protected $model;

	/**
	 * The resources that should be eager loaded
	 * @var array
	 */
	protected $eagerLoad;

	/**
	 * Set the model and the service listener
	 * @param ResourceServiceListenerContract $listener Listener class with methods to call after successful/failed operations
	 * @param BaseModel                       $model    The resource's model that the service will use
	 */
	public function __construct( ResourceServiceListenerContract $listener, BaseModel $model )
	{
		$this->model = $model;
		$this->eagerLoad = [];
		parent::__construct($listener);
	}

	/**
	 * Get all items of this resource
	 * @param  array $filters   Filters to apply to the model
	 * @param  array $eagerLoad Related models to eager load
	 * @return Collection       Query results
	 */
	public function all($filters = [], $eagerLoad = [])
	{
		$this->model = $this->filter($this->model, $filters);

		if( ! empty($eagerLoad) ) $this->with($eagerLoad);

		return $this->getQueryBuilder()->get();
	}

	/**
	 * Get a single resource item by its ID
	 * @param  integer $id        ID of the desired item
	 * @param  array $eagerLoad	  Related models to eager load
	 * @return Collection       Query results
	 */
	public function single($id, $eagerLoad = [])
	{
		if( ! empty($eagerLoad) ) return $this->model->with($eagerLoad)->findOrFail($id);

		return $this->model->findOrFail($id);
	}

	/**
	 * Get a key/value pair of all items of this resource
	 * @param  string $key     Model field for the array item's key
	 * @param  string $value   Model field for the array item's value
	 * @param  string $orderBy Model field to sort the array by
	 * @return array           Array of items
	 */
	public function lists($key, $value, $orderBy = 'id')
	{
		return $this->model->orderBy( $orderBy )->lists($key, $value);
	}

	/**
	 * Store a new resource item
	 * @param  array $input Raw user input
	 */
	public function store($input)
	{
		$this->model->fill($input);

		$validator = new $this->model->validator( $this->model->toArray() );
		$validator->scope(['store']);

		if ( $validator->fails() )
		{
			$this->errors = $validator->errors()->all();
			return parent::handleEvent( 'store', 'failed', $this->model );
		}
		else
		{
			$this->model->save();
			return parent::handleEvent( 'store', 'succeeded', $this->model );
		}
	}

	/**
	 * Update an existing resource item by ID
	 * @param  integer $id    Item's ID
	 * @param  array $input   Raw user input
	 */
	public function update($id, $input)
	{
		$this->model = $this->model->findOrFail($id);
		
		$this->model->fill($input);

		$validator = new $this->model->validator( $this->model->toArray() );
		$validator->scope(['update']);

		if ( $validator->fails() )
		{
			$this->errors = $validator->errors()->all();
			return parent::handleEvent( 'update', 'failed', $this->model );
		}
		else
		{
			$this->model->save();
			return parent::handleEvent( 'update', 'succeeded', $this->model );
		}
	}

	/**
	 * Destroy an existing resource item by ID
	 * @param  integer $id    Item's ID
	 */
	public function destroy($id)
	{
		$this->model = $this->model->findOrFail($id);
		if( $this->model->delete() )
		{
			unset($this->model);
			return parent::handleEvent( 'destroy', 'succeeded' );
		}
		$this->errors = ['Unable to destroy ' . $this->resource() ];
		return parent::handleEvent( 'destroy', 'failed', $this->model );
	}

	/**
	 * Get the resource ID of the current resource item
	 * @return integer    Item's ID
	 */
	public function resourceIds()
	{
		if( isset($this->model) ) return $this->model->id;
	}

}