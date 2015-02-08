<?php namespace Zeropingheroes\Lanager\Pages;

use Zeropingheroes\Lanager\FlatResourceService;

class PageService extends FlatResourceService {

	protected $resource = 'page';

	public function __construct( $listener )
	{
		parent::__construct($listener, new Page);
	}

	public function single($id)
	{
		return $this->model->with('children')->findOrFail($id);
	}

}