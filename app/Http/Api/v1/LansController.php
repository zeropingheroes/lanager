<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\Lans\LanService;
use Zeropingheroes\Lanager\Domain\Lans\LanTransformer;

class LansController extends ResourceServiceController {

	/**
	 * Set the service and transformer classes
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new LanService($this);
		$this->transformer = new LanTransformer;
	}

}