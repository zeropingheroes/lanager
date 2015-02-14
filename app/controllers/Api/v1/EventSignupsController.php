<?php namespace Zeropingheroes\Lanager\Api\v1;

use Zeropingheroes\Lanager\EventSignups\EventSignupService,
	Zeropingheroes\Lanager\EventSignups\EventSignupTransformer;

class EventSignupsController extends BaseController {

	public function __construct()
	{
		parent::__construct();
		$this->service = new EventSignupService($this);
		$this->transformer = new EventSignupTransformer;
	}

}