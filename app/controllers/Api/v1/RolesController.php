<?php namespace Zeropingheroes\Lanager\Api\v1;

use Zeropingheroes\Lanager\Roles\RoleService,
	Zeropingheroes\Lanager\Roles\RoleTransformer;

class RolesController extends BaseController {

	public function __construct()
	{
		parent::__construct();
		$this->service = new RoleService($this);
		$this->transformer = new RoleTransformer;
	}

}