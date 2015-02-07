<?php namespace Zeropingheroes\Lanager\EventTypes;

use Zeropingheroes\Lanager\SingularResourceService;

class EventTypeService extends SingularResourceService {

	protected $resource = 'event type';

	public function __construct( $listener )
	{
		parent::__construct($listener, new EventType);
	}

}