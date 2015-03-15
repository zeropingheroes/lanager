<?php namespace Zeropingheroes\Lanager\EventTypes;

use Zeropingheroes\Lanager\FlatResourceService;

class EventTypeService extends FlatResourceService {

	protected $resource = 'event-types';

	public function __construct( $listener )
	{
		parent::__construct($listener, new EventType);
	}

}