<?php namespace Zeropingheroes\Lanager\UserAchievements;

use Zeropingheroes\Lanager\BaseModel;

class UserAchievement extends BaseModel {

	/**
	 * Fields that can be mass assigned
	 * @var array
	 */
	protected $fillable = ['user_id', 'achievement_id', 'lan_id'];

	/**
	 * Validator class responsible for validating this model
	 * @var string
	 */
	public $validator = 'Zeropingheroes\Lanager\UserAchievements\UserAchievementValidator';

	/**
	 * A single user achievement (aka award) belongs to a single user
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Users\User');
	}

	/**
	 * A single user achievement (aka award) belongs to a single achievement
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function achievement()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Achievements\Achievement');
	}

	/**
	 * A single user achievement (aka award) belongs to a single LAN
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function lan()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Lans\Lan');
	}

}