<?php namespace Zeropingheroes\Lanager\Roles;

use League\Fractal;
use Zeropingheroes\Lanager\Users\UserTransformer;

class RoleTransformer extends Fractal\TransformerAbstract {

	/**
	 * Transform resource into standard output format with correct typing
	 * @param  object BaseModel   Resource being transformed
	 * @return array              Transformed object array ready for output
	 */
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