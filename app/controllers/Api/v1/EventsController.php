<?php namespace Zeropingheroes\Lanager\Api\v1;

use Zeropingheroes\Lanager\Events\EventService,
	Zeropingheroes\Lanager\Events\EventTransformer;

class EventsController extends BaseController {

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