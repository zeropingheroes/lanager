<?php namespace Zeropingheroes\Lanager\EventTypes;

use Zeropingheroes\Lanager\BaseResourceService,
	Zeropingheroes\Lanager\ResourceServiceContract;

class EventTypeService extends BaseResourceService implements ResourceServiceContract {

	public $resourceName = 'event type';

	public function __construct( $listener )
	{
		$this->listener = $listener;
		$this->model = new EventType;
	}

}