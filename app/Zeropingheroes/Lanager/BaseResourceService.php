<?php namespace Zeropingheroes\Lanager;

use Event;

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

	protected function handleEvent( $action, $outcome, $item = null)
	{
		$this->messages = trans('confirmation.after.resource.' . $action, ['resource' => trans('resources.' . $this->resource()) ]);
		
		$eventName =
		[
			'lanager',
			$this->resource(),
			$action,
			$outcome,
		];

		Event::fire( implode('.', $eventName) , $item);
		
		return $this->listener->{$action.$outcome}( $this );
	}

	protected function storeSucceeded( $item )
	{
		$this->messages = trans('confirmation.after.resource.store', ['resource' => trans('resources.' . $this->resource()) ]);
		Event::fire( 'lanager.' . $this->resource() . '.store', $item);
		return $this->listener->storeSucceeded( $this );
	}

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