<?php namespace Zeropingheroes\Lanager\Api;

class PagesController extends BaseController {
	
	protected $resourceTransformer = 'Zeropingheroes\Lanager\Pages\PageTransformer';

	protected $resourceService = 'Zeropingheroes\Lanager\Pages\PageService';

}