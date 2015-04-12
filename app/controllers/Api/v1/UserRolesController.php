<?php namespace Zeropingheroes\Lanager\Api\v1;

use Zeropingheroes\Lanager\UserRoles\UserRoleService,
	Zeropingheroes\Lanager\UserRoles\UserRoleTransformer;

class UserRolesController extends BaseController {

	/**
	 * Set the service and transformer classes
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new UserRoleService($this);
		$this->transformer = new UserRoleTransformer;
	}

}