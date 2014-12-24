<?php namespace Zeropingheroes\Lanager\Events;

use Zeropingheroes\Lanager\BaseResourceService,
	Zeropingheroes\Lanager\ResourceServiceContract;

class EventService extends BaseResourceService implements ResourceServiceContract {

	public $resourceName = 'event';

	public function __construct( $listener )
	{
		$this->listener = $listener;
		$this->model = new Event;
	}

	public function all()
	{
		return $this->model->with('type')->get();
	}

	public function single($id)
	{
		return $this->model->with('type')->findOrFail($id);
	}

}