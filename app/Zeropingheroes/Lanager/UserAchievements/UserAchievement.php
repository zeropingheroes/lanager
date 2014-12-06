<?php namespace Zeropingheroes\Lanager\UserAchievements;

use Zeropingheroes\Lanager\BaseModel;

class UserAchievement extends BaseModel {

	protected $fillable = ['user_id', 'achievement_id', 'lan_id'];

	public $validator = 'Zeropingheroes\Lanager\UserAchievements\UserAchievementValidator';

	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Users\User');
	}

	public function achievement()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Achievements\Achievement');
	}

	public function lan()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Lans\Lan');
	}

}