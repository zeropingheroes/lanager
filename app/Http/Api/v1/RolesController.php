<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\Roles\RoleService;
use Zeropingheroes\Lanager\Domain\Roles\RoleTransformer;

class RolesController extends ResourceServiceController {

	/**
	 * Set the service and transformer classes
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new RoleService($this);
		$this->transformer = new RoleTransformer;
	}

}