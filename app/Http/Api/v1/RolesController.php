<?php namespace Zeropingheroes\Lanager\Http\Api\v1;

use Zeropingheroes\Lanager\Domain\Roles\RoleService;
use Zeropingheroes\Lanager\Domain\Roles\RoleTransformer;
use Zeropingheroes\Lanager\Http\Api\v1\Traits\FlatResourceTrait;

class RolesController extends ResourceServiceController {

	use FlatResourceTrait;

	/**
	 * Set the service and transformer classes
	 */
	public function __construct()
	{
		parent::__construct();
		$this->service = new RoleService;
		$this->transformer = new RoleTransformer;
	}

}