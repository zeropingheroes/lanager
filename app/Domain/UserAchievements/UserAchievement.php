<?php namespace Zeropingheroes\Lanager\Domain\UserAchievements;

use Zeropingheroes\Lanager\Domain\BaseModel;

class UserAchievement extends BaseModel {

	protected $fillable = ['user_id', 'achievement_id', 'lan_id'];

	/**
	 * A single user achievement (aka award) belongs to a single user
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Domain\Users\User');
	}

	/**
	 * A single user achievement (aka award) belongs to a single achievement
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function achievement()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Domain\Achievements\Achievement');
	}

	/**
	 * A single user achievement (aka award) belongs to a single LAN
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function lan()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Domain\Lans\Lan');
	}

}