<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\EventTypes\EventTypeService;
use	Zeropingheroes\Lanager\Domain\EventTypes\EventTypeTransformer;

class EventTypesController extends ResourceServiceController {

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