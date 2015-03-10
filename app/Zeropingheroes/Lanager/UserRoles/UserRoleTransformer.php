<?php namespace Zeropingheroes\Lanager\UserRoles;

use League\Fractal;
use Zeropingheroes\Lanager\Users\UserTransformer,
	Zeropingheroes\Lanager\Roles\RoleTransformer;

class UserRoleTransformer extends Fractal\TransformerAbstract {

	protected $defaultIncludes = [
		'user',
		'role',
	];
	
	public function transform(UserRole $userRole)
	{
		return [
			'id'			=> (int) $userRole->id,
			'user_id'		=> (int) $userRole->user_id,
			'role_id'		=> (int) $userRole->role_id,
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => (url().'/user-roles/'. $userRole->id),
				]
			],
		];
	}

	public function includeUser(UserRole $userRole)
	{
		return $this->item($userRole->user()->first(), new UserTransformer);
	}

	public function includeRole(UserRole $userRole)
	{
		return $this->item($userRole->role()->first(), new RoleTransformer);
	}
}