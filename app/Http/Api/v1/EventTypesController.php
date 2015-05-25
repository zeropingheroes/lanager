<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\EventTypes\EventTypeService;
use	Zeropingheroes\Lanager\Domain\EventTypes\EventTypeTransformer;
use Zeropingheroes\Lanager\Http\Api\v1\Traits\FlatResourceTrait;

class EventTypesController extends ResourceServiceController {

	use FlatResourceTrait;

	/**
	 * Set the service and transformer classes
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new EventTypeService;
		$this->transformer = new EventTypeTransformer;
	}

}