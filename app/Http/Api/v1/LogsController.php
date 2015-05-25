<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\Logs\LogService;
use	Zeropingheroes\Lanager\Domain\Logs\LogTransformer;
use Zeropingheroes\Lanager\Http\Api\v1\Traits\FlatResourceTrait;

class LogsController extends ResourceServiceController {

	use FlatResourceTrait;

	/**
	 * Set the service and transformer classes
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new LogService;
		$this->transformer = new LogTransformer;

		// require API auth for reading logs
		$this->protect(['index']);
	}

}