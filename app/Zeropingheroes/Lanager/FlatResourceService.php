<?php namespace Zeropingheroes\Lanager;

abstract class FlatResourceService extends BaseResourceService {

	protected $model;

	public function __construct( ResourceServiceListenerContract $listener, BaseModel $model )
	{
		$this->model = $model;
		parent::__construct($listener);
	}

	public function all($filters = [], $eagerLoad = [])
	{
		$this->model = $this->filter($this->model, $filters);

		if( ! empty($eagerLoad) ) return $this->model->with($eagerLoad)->get();

		return $this->model->get();
	}

	public function single($id, $eagerLoad = [])
	{
		if( ! empty($eagerLoad) ) return $this->model->with($eagerLoad)->findOrFail($id);

		return $this->model->findOrFail($id);
	}

	public function lists($key, $value, $orderBy = 'id')
	{
		return $this->model->orderBy( $orderBy )->lists($key, $value);
	}

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

	public function resourceIds()
	{
		if( isset($this->model) ) return $this->model->id;
	}

}