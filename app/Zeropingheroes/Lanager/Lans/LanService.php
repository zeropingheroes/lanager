<?php namespace Zeropingheroes\Lanager\Lans;

use Zeropingheroes\Lanager\BaseResourceService,
	Zeropingheroes\Lanager\ResourceServiceContract;

class LanService extends BaseResourceService implements ResourceServiceContract {

	public $resourceName = 'lan';

	public function __construct( $listener )
	{
		$this->listener = $listener;
		$this->model = new Lan;
	}

	public function all()
	{
		return $this->model->orderby('start', 'asc')->get();
	}

}