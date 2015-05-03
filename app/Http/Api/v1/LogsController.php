<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\Logs\LogService;
use	Zeropingheroes\Lanager\Domain\Logs\LogTransformer;

class LogsController extends ResourceServiceController {

	/**
	 * Set the service and transformer classes
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new LogService($this);
		$this->transformer = new LogTransformer;

		// require API auth for reading logs
		$this->protect(['index']);
	}

}