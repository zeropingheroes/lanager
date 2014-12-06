<?php namespace Zeropingheroes\Lanager\Achievements;

use Zeropingheroes\Lanager\BaseModel;

class Achievement extends BaseModel {

	protected $fillable = ['name', 'description'];

	public $validator = 'Zeropingheroes\Lanager\Achievements\AchievementValidator';

	public function userAchievements()
	{
		return $this->hasMany('Zeropingheroes\Lanager\UserAchievements\UserAchievement');
	}

}