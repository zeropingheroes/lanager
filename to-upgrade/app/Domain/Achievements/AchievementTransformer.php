<?php namespace Zeropingheroes\Lanager\Domain\Achievements;

use League\Fractal\TransformerAbstract;

class AchievementTransformer extends TransformerAbstract {

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