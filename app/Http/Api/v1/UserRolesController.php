<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\UserRoles\UserRoleService;
use Zeropingheroes\Lanager\Domain\UserRoles\UserRoleTransformer;

class UserRolesController extends ResourceServiceController {

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