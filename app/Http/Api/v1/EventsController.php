<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\Events\EventService;
use Zeropingheroes\Lanager\Domain\Events\EventTransformer;

class EventsController extends ResourceServiceController {

	/**
	 * Set the service and transformer classes
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new EventService($this);
		$this->transformer = new EventTransformer;
		$this->draftable = true;
	}

}