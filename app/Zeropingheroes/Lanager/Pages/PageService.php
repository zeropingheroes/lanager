<?php namespace Zeropingheroes\Lanager\Pages;

use Zeropingheroes\Lanager\BaseResourceService,
	Zeropingheroes\Lanager\ResourceServiceContract;

class PageService extends BaseResourceService implements ResourceServiceContract {

	public $model = 'Zeropingheroes\Lanager\Pages\Page';

	public $resourceName = 'page';

}