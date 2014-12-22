<?php namespace Zeropingheroes\Lanager\Api\v1;

use Zeropingheroes\Lanager\Pages\PageService,
	Zeropingheroes\Lanager\Pages\PageTransformer;

class PagesController extends BaseController {

	public function __construct()
	{
		parent::__construct();
		$this->service = new PageService($this);
		$this->transformer = new PageTransformer;
	}

}