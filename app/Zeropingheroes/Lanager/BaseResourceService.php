<?php namespace Zeropingheroes\Lanager;

abstract class BaseResourceService {

	protected $resource;
	protected $listener;
	protected $errors;
	protected $messages;

	public function __construct( ResourceServiceListenerContract $listener )
	{
		$this->listener = $listener;
	}

	public function resource()
	{
		return $this->resource;
	}

	public function errors()
	{
		if( !is_array($this->errors) ) $this->errors = [$this->errors];
		return $this->errors;
	}

	public function messages()
	{
		return $this->messages;
	}

	public function filter( $model, array $filters)
	{
		if( isset($filters['orderBy']) )
		{
			if( starts_with($filters['orderBy'], '-') )
			{
				$field = ltrim($filters['orderBy'], '-');
				$direction = 'desc';
			}
			else
			{
				$field = $filters['orderBy'];
				$direction = 'asc';
			}
			$model = $model->orderBy( $field, $direction );
		}

		if( isset($filters['skip']) && is_numeric($filters['skip']) )
		{
			$model = $model->skip($filters['skip']);
		}

		if( isset($filters['take']) && is_numeric($filters['take']) )
		{
			$model = $model->take($filters['take']);
		}

		// Take any other query parameters and use them as where clauses
		$fields = array_except($filters, ['orderBy', 'skip', 'take']);

		foreach($fields as $field => $value)
		{
			$model = $model->where( $field, $value);
		}

		return $model;
	}

}