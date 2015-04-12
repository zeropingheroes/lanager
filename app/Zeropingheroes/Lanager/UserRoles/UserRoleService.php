<?php namespace Zeropingheroes\Lanager\UserRoles;

use Zeropingheroes\Lanager\FlatResourceService;
use Auth;

class UserRoleService extends FlatResourceService {

	protected $resource = 'user-roles';

	public function __construct( $listener )
	{
		parent::__construct($listener, new UserRole);
	}

	public function store($input)
	{
		$input['assigned_by'] = Auth::user()->id;
		
		return parent::store($input);
	}

	public function update( $id, $input)
	{
		$this->errors = 'This resource does not support being updated';
		return $this->listener->updateFailed($this);
	}
}