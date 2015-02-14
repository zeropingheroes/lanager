<?php namespace Zeropingheroes\Lanager\Roles;

use League\Fractal;
use Zeropingheroes\Lanager\Users\UserTransformer;

class RoleTransformer extends Fractal\TransformerAbstract {

	public function transform(Role $role)
	{
		return [
			'id'			=> (int) $role->id,
			'name'			=> $role->name,
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => (url().'/roles/'. $role->id),
				]
			],
		];
	}

}