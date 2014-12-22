<?php namespace Zeropingheroes\Lanager\Achievements;

use League\Fractal;

class AchievementTransformer extends Fractal\TransformerAbstract {
	
	public function transform(Achievement $achievement)
	{
		return [
			'id'			=> (int) $achievement->id,
			'name'			=> $achievement->name,
			'description'	=> $achievement->description,
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => ('/achievements/'. $achievement->id),
				]
			],
		];
	}
}