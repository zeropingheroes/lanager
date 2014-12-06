<?php namespace Zeropingheroes\Lanager\Lans;

use Zeropingheroes\Lanager\BaseModel;

class Lan extends BaseModel {

	protected $fillable = ['name', 'start', 'end'];

	public function userAchievement()
	{
		return $this->hasMany('Zeropingheroes\Lanager\UserAchievements\UserAchievement');
	}

}