<?php namespace Zeropingheroes\Lanager\Api;

use Zeropingheroes\Lanager\Pages\PageService;

class PagesController extends BaseController {
	
	protected $resourceTransformer = 'Zeropingheroes\Lanager\Pages\PageTransformer';

	public function __construct()
	{
		$this->service = new PageService($this);
	}

}