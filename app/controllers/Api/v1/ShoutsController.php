<?php namespace Zeropingheroes\Lanager\Api\v1;

use Zeropingheroes\Lanager\Shouts\ShoutService,
	Zeropingheroes\Lanager\Shouts\ShoutTransformer;

class ShoutsController extends BaseController {

	public function __construct()
	{
		parent::__construct();
		$this->service = new ShoutService($this);
		$this->transformer = new ShoutTransformer;
	}

}