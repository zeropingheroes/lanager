<?php namespace Zeropingheroes\Lanager\Roles;

use Zeropingheroes\Lanager\BaseModel;

class Role extends BaseModel {

	protected $fillable = ['name'];

	public function users()
	{
		return $this->belongsToMany('Zeropingheroes\Lanager\Users\User', 'role_user');
	}

}