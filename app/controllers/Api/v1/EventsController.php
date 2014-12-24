<?php namespace Zeropingheroes\Lanager\Api\v1;

use Zeropingheroes\Lanager\Events\EventService,
	Zeropingheroes\Lanager\Events\EventTransformer;

class EventsController extends BaseController {

	public function __construct()
	{
		parent::__construct();
		$this->service = new EventService($this);
		$this->transformer = new EventTransformer;
	}

}