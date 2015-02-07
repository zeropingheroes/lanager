<?php namespace Zeropingheroes\Lanager\Events;

use Zeropingheroes\Lanager\BaseResourceService;

class EventService extends BaseResourceService {

	public $resourceName = 'event';

	public function __construct( $listener )
	{
		$this->listener = $listener;
		$this->model = new Event;
	}

	public function all()
	{
		return $this->model->with('type')->orderby('start', 'asc')->get();
	}

	public function single($id)
	{
		return $this->model->with('type')->findOrFail($id);
	}

}