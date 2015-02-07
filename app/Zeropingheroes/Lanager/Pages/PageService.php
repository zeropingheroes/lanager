<?php namespace Zeropingheroes\Lanager\Pages;

use Zeropingheroes\Lanager\BaseResourceService;

class PageService extends BaseResourceService {

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