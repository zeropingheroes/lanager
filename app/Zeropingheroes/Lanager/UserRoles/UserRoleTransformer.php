<?php namespace Zeropingheroes\Lanager\UserRoles;

use League\Fractal;

class UserRoleTransformer extends Fractal\TransformerAbstract {
	
	public function transform(UserRole $userRole)
	{
		return [
			'id'			=> (int) $userRole->id,
			'user'			=> $userRole->user,
			'role'			=> $userRole->role,
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => ('/user-roles/'. $userRole->id),
				]
			],
		];
	}
}