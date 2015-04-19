<?php namespace Zeropingheroes\Lanager;

use Event;

abstract class BaseResourceService {

	/**
	 * The canonical application-wide name for the resource that this service provides for
	 * @var string
	 */
	protected $resource;

	/**
	 * The class which this class will call after successful / failed operations
	 * @var object ResourceServiceListenerContract
	 */
	protected $listener;

	/**
	 * Error messages(s) set during service operation
	 * @var array
	 */
	protected $errors;

	/**
	 * Information message(s) set during service operation
	 * @var array
	 */
	protected $messages;

	/**
	 * The resources that should be eager loaded
	 * @var array
	 */
	protected $eagerLoad;

	/**
	 * The number of items that should be skipped
	 * @var integer
	 */
	protected $skip;

	/**
	 * The maximum number of items that should be returned
	 * @var array
	 */
	protected $take;

	/**
	 * Instantiate the service with a listener that the service can call methods
	 * on after action success/failure
	 * @param object ResourceServiceListenerContract $listener Listener class with required methods
	 */
	public function __construct( ResourceServiceListenerContract $listener )
	{
		$this->listener = $listener;
		$this->eagerLoad = [];
	}

	/**
	 * Read only getter for resource name
	 * @return string
	 */
	public function resource()
	{
		return $this->resource;
	}

	/**
	 * Read only getter for error messages
	 * @return array
	 */
	public function errors()
	{
		if( !is_array($this->errors) ) $this->errors = [$this->errors];
		return $this->errors;
	}

	/**
	 * Read only getter for info messages
	 * @return array
	 */
	public function messages()
	{
		return $this->messages;
	}

	/**
	 * Handle an action's result after it has been called
	 * @param  string 		$action  Name of the action
	 * @param  string 		$outcome Result of the action (success/failure)
	 * @param  BaseModel 	$item    Item the action was performed on
	 */
	protected function handleEvent( $action, $outcome, $item = null)
	{
		if( $outcome == 'succeeded' ) $this->messages = trans('confirmation.after.resource.' . $action, ['resource' => trans('resources.' . $this->resource()) ]);
		if( $outcome == 'failed' ) $this->messages = 'Unable to ' . $action . ' ' . trans('resources.' . $this->resource() );
		
		$eventName =
		[
			'lanager.services',
			$this->resource(),
			$action,
			$outcome,
		];

		$eventParameters['messages'] = $this->messages();
		$eventParameters['errors'] = $this->errors();
		$eventParameters['item'] = $item;

		Event::fire( implode('.', $eventName) , [$eventParameters] );
		
		return $this->listener->{$action.$outcome}( $this );
	}

	/**
	 * Apply filters to query results
	 * @param  object BaseModel $model 	Model to apply filters to
	 * @param  array  $filters 			Filters to apply
	 * @return object BaseModel			Filtered model
	 */
	public function filter( $model, array $filters)
	{
		if( isset($filters['orderBy']) )
		{
			if( ! is_array( $filters['orderBy'] ) && str_contains( $filters['orderBy'], ',') )
			{
				$filters['orderBy'] = explode(',', $filters['orderBy']);
			}
			elseif( !is_array($filters['orderBy']) )
			{
				$filters['orderBy'] = [$filters['orderBy']];
			}
			foreach($filters['orderBy'] as $value)
			{
				if( starts_with($value, '-') )
				{
					$field = ltrim($value, '-');
					$direction = 'desc';
				}
				else
				{
					$field = $value;
					$direction = 'asc';
				}
				$model = $model->orderBy( $field, $direction );				
			}

		}

		// Take any other query parameters and use them as where clauses
		$fields = array_except($filters, ['orderBy', 'skip', 'take']);

		foreach($fields as $field => $value)
		{
			if( is_array($value) )
			{
				$model = $model->whereIn( $field, $value );
			}
			else
			{
				$model = $model->where( $field, $value);
			}
		}

		return $model;
	}


	/**
	 * Returns a Builder instance for use in constructing a query, honouring the 
	 * current filters. Resets the filters, ready for the next query.
	 * @return \Illuminate\Database\Query\Builder
	 */
	protected function getQueryBuilder()
	{
		$builder = with( $this->model )->newQuery();
	
		if ($this->eagerLoad)	$builder->with( $this->eagerLoad );
		if ($this->skip)		$builder->skip( $this->skip );
		if ($this->take)		$builder->take( $this->take );
	
		$this->eagerLoad = null;
		$this->skip = null;
		$this->take = null;

		return $builder;
	}

	/**
	 * Eager loads additional related resources
	 * @param array $resources Resources to eager load
	 * @return self
	 */
	public function with( $resources )
	{
		$this->eagerLoad = $resources;
		return $this;
	}

	/**
	 * Sets the number of items that should be skipped
	 * @param integer $take
	 * @return self
	 */
	public function skip( $skip )
	{
		$this->skip = $skip;
		return $this;
	}

	/**
	 * Sets the maximum number of items that should be returned
	 * @param integer $take
	 * @return self
	 */
	public function take( $take )
	{
		$this->take = $take;
		return $this;
	}

}