<?php namespace Zeropingheroes\Lanager;

abstract class BaseResourceService {

	protected $listener;
	
	public $model;

	public $resourceName;

	public $errors;

	public $messages;

	public function __construct($listener)
	{
		$this->listener = $listener;
	}

	public function all()
	{
		return call_user_func($this->model . '::all');
	}

	public function single($id)
	{
		return call_user_func($this->model . '::findOrFail', $id);
	}

	public function store($input)
	{
		$this->model = new $this->model;
		$this->model->fill($input);

		$validator = new $this->model->validator( $this->model->toArray() );

		if ( $validator->fails() )
		{
			$this->errors = $validator->errors()->all();
			return $this->listener->storeFailed( $this );
		}
		else
		{
			$this->model->save();
			$this->messages = trans('confirmation.after.resource.store', ['resource' => trans('resources.' . $this->resourceName) ]);
			return $this->listener->storeSucceeded( $this );
		}
	}

	public function update($id, $input)
	{
		$this->model = call_user_func($this->model . '::findOrFail', $id);

		$this->model->fill($input);

		$validator = new $this->model->validator( $this->model->toArray() );

		if ( $validator->fails() )
		{
			$this->errors = $validator->errors()->all();
			return $this->listener->updateFailed( $this );
		}
		else
		{
			$this->model->save();
			$this->messages = trans('confirmation.after.resource.update', ['resource' => trans('resources.' . $this->resourceName) ]);
			return $this->listener->updateSucceeded( $this );
		}
	}

	public function destroy($id)
	{
		$this->model = call_user_func($this->model . '::findOrFail', $id);
		if( $this->model->delete() ) return $this->listener->destroySucceeded( $this );
		$this->errors = ['Unable to destroy ' . $this->resourceName ];
		return $this->listener->destroyFailed( $this );
	}
		
}