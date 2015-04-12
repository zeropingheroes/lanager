<?php namespace Zeropingheroes\Lanager\Achievements;

use League\Fractal;

class AchievementTransformer extends Fractal\TransformerAbstract {

	/**
	 * Transform resource into standard output format with correct typing
	 * @param  object BaseModel   Resource being transformed
	 * @return array              Transformed object array ready for output
	 */
	public function transform(Achievement $achievement)
	{
		return [
			'id'			=> (int) $achievement->id,
			'name'			=> $achievement->name,
			'description'	=> $achievement->description,
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => (url().'/achievements/'. $achievement->id),
				]
			],
		];
	}
}