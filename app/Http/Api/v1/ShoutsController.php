<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\Shouts\ShoutService;
use Zeropingheroes\Lanager\Domain\Shouts\ShoutTransformer;

class ShoutsController extends ResourceServiceController {

	/**
	 * Set the service and transformer classes
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new ShoutService($this);
		$this->transformer = new ShoutTransformer;
	}

}