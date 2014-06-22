<?php namespace Zeropingheroes\Lanager\Roles;

use Zeropingheroes\Lanager\BaseModel;

class Role extends BaseModel {

	public function users()
	{
		return $this->belongsToMany('Zeropingheroes\Lanager\Users\User', 'role_user');
	}

}