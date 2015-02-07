<?php namespace Zeropingheroes\Lanager\Lans;

use Zeropingheroes\Lanager\SingularResourceService;

class LanService extends SingularResourceService  {

	protected $resource = 'lan';

	public function __construct( $listener )
	{
		parent::__construct($listener, new Lan);
	}

	public function all()
	{
		return $this->model->orderby('start', 'asc')->get();
	}

}