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

}