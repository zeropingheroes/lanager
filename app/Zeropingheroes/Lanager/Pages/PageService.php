<?php namespace Zeropingheroes\Lanager\Pages;

use Zeropingheroes\Lanager\BaseResourceService,
	Zeropingheroes\Lanager\ResourceServiceContract;

class PageService extends BaseResourceService implements ResourceServiceContract {

	public $resourceName = 'page';

	public function __construct( $listener )
	{
		$this->listener = $listener;
		$this->model = new Page;
	}

	public function single($id)
	{
		return $this->model->with('children')->findOrFail($id);
	}

}