<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\EventSignups\EventSignupService;
use	Zeropingheroes\Lanager\Domain\EventSignups\EventSignupTransformer;

class EventSignupsController extends ResourceServiceController {

	/**
	 * Set the service and transformer classes
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new EventSignupService($this);
		$this->transformer = new EventSignupTransformer;
	}

}