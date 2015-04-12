<?php namespace Zeropingheroes\Lanager\Lans;

use League\Fractal;

class LanTransformer extends Fractal\TransformerAbstract {

	/**
	 * Transform resource into standard output format with correct typing
	 * @param  object BaseModel   Resource being transformed
	 * @return array              Transformed object array ready for output
	 */
	public function transform(Lan $lan)
	{
		return [
			'id'			=> (int) $lan->id,
			'name'			=> $lan->name,
			'start'			=> date('c',strtotime($lan->start)),
			'end'			=> date('c',strtotime($lan->end)),
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => (url().'/lans/'. $lan->id),
				]
			],
		];
	}
}