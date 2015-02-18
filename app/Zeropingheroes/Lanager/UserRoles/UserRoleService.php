<?php namespace Zeropingheroes\Lanager\UserRoles;

use Zeropingheroes\Lanager\FlatResourceService;

class UserRoleService extends FlatResourceService {

	protected $resource = 'user-role';

	public function __construct( $listener )
	{
		parent::__construct($listener, new UserRole);
	}

	public function update( $id, $input)
	{
		$this->errors = 'This resource does not support being updated';
		return $this->listener->updateFailed($this);
	}
}