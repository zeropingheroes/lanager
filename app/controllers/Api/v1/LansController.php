<?php namespace Zeropingheroes\Lanager\Api\v1;

use Zeropingheroes\Lanager\Lans\LanService,
	Zeropingheroes\Lanager\Lans\LanTransformer;

class LansController extends BaseController {

	public function __construct()
	{
		parent::__construct();
		$this->service = new LanService($this);
		$this->transformer = new LanTransformer;
	}

}