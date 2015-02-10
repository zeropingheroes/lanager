<?php namespace Zeropingheroes\Lanager;

use InvalidArgumentException, ReflectionClass;

abstract class NestedResourceService extends BaseResourceService {

	protected $models;

	public function __construct( ResourceServiceListenerContract $listener, array $models )
	{
		foreach( $models as $model )
		{
			if( ! ($model instanceof BaseModel) ) throw new InvalidArgumentException('Models must be instances of BaseModel');
		}

		$this->models = $models;
		parent::__construct($listener);
	}

	private function nestedFindOrFail(array $ids)
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
			if( ($i+1) < count($models) )
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

	public function all( array $ids )
	{
		return $this->nestedFindOrFail( $ids )->get();
	}

	public function single( array $ids )
	{
		return $this->nestedFindOrFail( $ids );
	}

	public function store( array $ids, $input )
	{
		$parent = $this->nestedFindOrFail( $ids );

		// get an instance of the model we want to create
		$child = end($this->models);

		$child = $child->fill($input);

		// find the name of the foreign key field for the parent of this new model 
		$foreignKeyField = substr($parent->getForeignKey(), strrpos($parent->getForeignKey(), '.') + 1);
		
		// set this new model's foreign key field for the parent to the last id
		$child->{$foreignKeyField} = end($ids);

		$validator = new $child->validator( $child->toArray() );

		if ( $validator->fails() )
		{
			$this->errors = $validator->errors()->all();
			return $this->listener->storeFailed( $this );
		}
		else
		{
			$parent->save($child);
			$this->messages = trans('confirmation.after.resource.store', ['resource' => trans('resources.' . $this->resource()) ]);
			return $this->listener->storeSucceeded( $this );
		}
	}

}