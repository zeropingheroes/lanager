<?php namespace Zeropingheroes\Lanager;

abstract class BaseResourceService {

	protected $listener;
	protected $model;
	protected $resourceName;
	protected $errors;
	protected $messages;

	public function all()
	{
		return $this->model->all();
	}

	public function single($id)
	{
		return $this->model->findOrFail($id);
	}

	public function lists($key, $value)
	{
		return $this->model->lists($key, $value);
	}

	public function store($input)
	{
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
		$this->model = $this->model->findOrFail($id);
		
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
		$this->model = $this->model->findOrFail($id);
		if( $this->model->delete() )
		{
			$this->messages = trans('confirmation.after.resource.destroy', ['resource' => trans('resources.' . $this->resourceName) ]);
			return $this->listener->destroySucceeded( $this );
		}
		$this->errors = ['Unable to destroy ' . $this->resourceName ];
		return $this->listener->destroyFailed( $this );
	}

	// Getters
	public function model()
	{
		return $this->model;
	}

	public function resourceName()
	{
		return $this->resourceName;
	}

	public function errors()
	{
		return $this->errors;
	}

	public function messages()
	{
		return $this->messages;
	}

}