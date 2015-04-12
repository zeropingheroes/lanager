<?php namespace Zeropingheroes\Lanager\Api\v1;

use Zeropingheroes\Lanager\EventTypes\EventTypeService,
	Zeropingheroes\Lanager\EventTypes\EventTypeTransformer;

class EventTypesController extends BaseController {

	/**
	 * Set the service and transformer classes
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new EventTypeService($this);
		$this->transformer = new EventTypeTransformer;
	}

}