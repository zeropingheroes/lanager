<?php namespace Zeropingheroes\Lanager\Pages;

use Zeropingheroes\Lanager\BaseResourceService,
	Zeropingheroes\Lanager\ResourceServiceContract;

class PageService extends BaseResourceService implements ResourceServiceContract {

	public $model = 'Zeropingheroes\Lanager\Pages\Page';

	public $resourceName = 'page';

	public function single($id)
	{
		$page = call_user_func_array($this->model . '::with', ['children']);
		return $page->findOrFail($id);
	}

}