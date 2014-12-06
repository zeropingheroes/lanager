<?php namespace Zeropingheroes\Lanager\UserRoles;

use Zeropingheroes\Lanager\BaseModel;
use Zeropingheroes\Lanager\Users\User;

class UserRole extends BaseModel {

	protected $table = 'user_roles';

	protected $fillable = ['user_id', 'role_id'];

	public $validator = 'Zeropingheroes\Lanager\UserRoles\UserRoleValidator';

	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Users\User');
	}

	public function role()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Roles\Role');
	}

}
