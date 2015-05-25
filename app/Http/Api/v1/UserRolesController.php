<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\UserRoles\UserRoleService;
use Zeropingheroes\Lanager\Domain\UserRoles\UserRoleTransformer;
use Zeropingheroes\Lanager\Http\Api\v1\Traits\FlatResourceTrait;

class UserRolesController extends ResourceServiceController {

	use FlatResourceTrait;

	/**
	 * Set the service and transformer classes
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new UserRoleService;
		$this->transformer = new UserRoleTransformer;
	}

}