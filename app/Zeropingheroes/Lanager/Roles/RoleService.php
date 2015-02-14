<?php namespace Zeropingheroes\Lanager\Roles;

use Zeropingheroes\Lanager\FlatResourceService;

class RoleService extends FlatResourceService {

	protected $resource = 'role';

	public function __construct( $listener )
	{
		parent::__construct($listener, new Role);
	}

}