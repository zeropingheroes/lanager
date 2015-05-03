<?php namespace Zeropingheroes\Lanager\Domain\Roles;

use League\Fractal\TransformerAbstract;
use Zeropingheroes\Lanager\Domain\Users\UserTransformer;

class RoleTransformer extends TransformerAbstract {

	/**
	 * Transform resource into standard output format with correct typing
	 * @param  object BaseModel   Resource being transformed
	 * @return array              Transformed object array ready for output
	 */
	public function transform( Role $role )
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