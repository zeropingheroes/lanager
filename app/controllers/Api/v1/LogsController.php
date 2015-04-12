<?php namespace Zeropingheroes\Lanager\Api\v1;

use Zeropingheroes\Lanager\Logs\LogService,
	Zeropingheroes\Lanager\Logs\LogTransformer;

class LogsController extends BaseController {

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