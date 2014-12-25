<?php namespace Zeropingheroes\Lanager\Lans;

use League\Fractal;

class LanTransformer extends Fractal\TransformerAbstract {
	
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
					'uri' => ('/lans/'. $lan->id),
				]
			],
		];
	}
}