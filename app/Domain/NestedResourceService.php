<?php namespace Zeropingheroes\Lanager\Domain;

use InvalidArgumentException;
use ReflectionClass;

abstract class NestedResourceService extends BaseResourceService {

	/**
	 * Array of parent model objects for the nest of models that precede the model the service will process
	 * @var array
	 */
	protected $models;

	/**
	 * Array of parent resource IDs for the nest of specific resources that precede the resource the service will process
	 * @var array
	 */
	protected $ids;

	/**
	 * Set the models and the service listener
	 * @param ResourceServiceListenerContract $listener Listener class with methods to call after successful/failed operations
	 * @param array                       $models    The resource's model nest that the service will use
	 */
	public function __construct( ResourceServiceListenerContract $listener, array $models )
	{
		foreach( $models as $model )
		{
			if( ! ($model instanceof BaseModel) ) throw new InvalidArgumentException('Models must be instances of BaseModel');
		}

		$this->models = $models;
		parent::__construct($listener);
	}

	/**
	 * Get all items of this resource
	 * @param  array $ids       Array of parent resource IDs for the nest of specific resources that precede the resource
	 * @param  array $options   Filters to apply to the model
	 * @param  array $eagerLoad Related models to eager load
	 * @return Collection       Query results
	 */
	public function all( array $ids, $options = [], $eagerLoad = [] )
	{
		$model = $this->nestedFindOrFail( $ids );
		$model = $this->filter($model, $options);

		if( ! empty($eagerLoad) ) return $model->with($eagerLoad)->get();

		return $model->get();
	}

	/**
	 * Get a single resource item by its ID (and parent item IDs)
	 * @param  array $ids       Array of parent resource IDs for the nest of specific resources that precede the resource
	 * @return Collection       Query results
	 */
	public function single( array $ids )
	{
		$item = $this->nestedFindOrFail( $ids );
		$this->ids = $ids;
		return $item;
	}

	/**
	 * Store a new resource item
	 * @param  array $ids       Array of parent resource IDs for the nest of specific resources that precede the resource
	 * @param  array $input     Raw user input
	 */
	public function store( array $ids, $input )
	{
		$parent = $this->nestedFindOrFail( $ids );
		$this->ids = $ids;

		// get an instance of the model we want to create
		$child = end($this->models);

		$child = $child->fill($input);

		// find the name of the foreign key field for the parent of this new model 
		$foreignKeyField = substr($parent->getForeignKey(), strrpos($parent->getForeignKey(), '.') + 1);
		
		// set this new model's foreign key field for the parent to the last id
		$child->{$foreignKeyField} = end($ids);

		$validator = new $child->validator( $child->toArray() );
		$validator->scope(['store']);

		if ( $validator->fails() )
		{
			$this->errors = $validator->errors()->all();
			return parent::handleEvent( 'store', 'failed', $child );
		}
		else
		{
			$parent->save($child);
			return parent::handleEvent( 'store', 'succeeded', $child );
		}
	}

	/**
	 * Update an existing resource item by ID
	 * @param  array $ids       Array of parent resource IDs for the nest of specific resources that precede the resource
	 * @param  array $input     Raw user input
	 */
	public function update( array $ids, $input )
	{
		$item = $this->nestedFindOrFail( $ids );
		$this->ids = $ids;
		$item = $item->fill($input);

		$validator = new $item->validator( $item->toArray() );
		$validator->scope(['update']);

		if ( $validator->fails() )
		{
			$this->errors = $validator->errors()->all();
			return parent::handleEvent( 'update', 'failed', $item );
		}
		else
		{
			$item->save();
			return parent::handleEvent( 'update', 'succeeded', $item );
		}
	}

	/**
	 * Destroy an existing resource item by ID
	 * @param  array $ids       Array of parent resource IDs for the nest of specific resources that precede the resource
	 */
	public function destroy( array $ids )
	{
		$item = $this->nestedFindOrFail( $ids );
		$this->ids = $ids;
		if( $item->delete() )
		{
			array_pop($this->ids);
			return parent::handleEvent( 'destroy', 'succeeded' );
		}
		$this->errors = ['Unable to destroy ' . $this->resource() ];
		return parent::handleEvent( 'destroy', 'failed', $item );
	}

	/**
	 * Get the parent resource item for a given nest of IDs
	 * @param  array $ids       Array of parent resource IDs for the nest of specific resources that precede the resource
	 * @return Collection       Query results
	 */
	public function parent( array $ids )
	{
		return $this->nestedFindOrFail( $ids, false );
	}

	/**
	 * Get the resource IDs of the nest of resource types
	 * @return integer    Item's ID
	 */
	public function resourceIds()
	{
		if( isset($this->ids) ) return $this->ids;
	}

	/**
	 * Verify each resource item of given ID exists
	 * @param  array $ids              Array of parent resource IDs for the nest of specific resources that precede the resource
	 * @param  boolean $fetchLastChild Once parent resources are verified to exist, get the last child or not
	 * @return BaseModel $model        Model of last parent or last child, depending on $fetchLastChild
	 */
	private function nestedFindOrFail(array $ids, $fetchLastChild = true)
	{
		$models = $this->models;

		// Only accept one less ID than the number of models in the nest
		if( (count($models) - count($ids)) > 1 )
		{
			throw new InvalidArgumentException('Expected a minimum of ' . count($models)-1 . ' IDs but ' . count($ids) . ' given' );
		}

		$i = 0;
		foreach( $ids as $id )
		{
			// initially, verify the first model of given id exists (and fetch it)
			if( $i == 0 ) $model = $models[$i]->findOrFail($id);

			// after 1st loop iteration, verify the current model of given id exists (and fetch it)
			if( $i > 0 ) $model = $model->findOrFail($id);

			// if we have not yet reached the bottom of the nest 
			if( ( ($i+1) < count($models) ) && $fetchLastChild )
			{
				// get method name of the next model in the nest
				$children = str_plural((new ReflectionClass($models[$i+1]))->getShortName());

				// fetch all items of the next model, which belong to the current model
				$model = $model->{$children}();
			}

			$i++;
		}
		return $model;
	}
}