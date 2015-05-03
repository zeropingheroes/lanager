<?php namespace Zeropingheroes\Lanager\Domain\UserAchievements;

use League\Fractal\TransformerAbstract;

class UserAchievementTransformer extends TransformerAbstract {

	/**
	 * Transform resource into standard output format with correct typing
	 * @param  object BaseModel   Resource being transformed
	 * @return array              Transformed object array ready for output
	 */
	public function transform(UserAchievement $userAchievement)
	{
		return [
			'id'			=> (int) $userAchievement->id,
			'user_id'		=> (int) $userAchievement->user_id,
			'achievement_id'=> (int) $userAchievement->achievement_id,
			'lan_id'		=> (int) $userAchievement->lan_id,
			'links'			=> [
				[
					'rel' => 'self',
					'uri' => (url().'/user-achievements/'. $userAchievement->id),
				]
			],
		];
	}
}