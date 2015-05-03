<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\Pages\PageService;
use Zeropingheroes\Lanager\Domain\Pages\PageTransformer;

class PagesController extends ResourceServiceController {

	/**
	 * Set the service and transformer classes
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new PageService($this);
		$this->transformer = new PageTransformer;
	}

}