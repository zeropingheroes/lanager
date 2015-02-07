<?php namespace Zeropingheroes\Lanager\EventTypes;

use Zeropingheroes\Lanager\BaseResourceService;

class EventTypeService extends BaseResourceService {

	public $resourceName = 'event type';

	public function __construct( $listener )
	{
		$this->listener = $listener;
		$this->model = new EventType;
	}

}