<?php namespace Zeropingheroes\Lanager\Lans;

use Zeropingheroes\Lanager\BaseModel;
use Laracasts\Presenter\PresentableTrait;

class Lan extends BaseModel {

	use PresentableTrait;

	/**
	 * Fields that can be mass assigned
	 * @var array
	 */
	protected $fillable = ['name', 'start', 'end'];

	/**
	 * Validator class responsible for validating this model
	 * @var string
	 */
	public $validator = 'Zeropingheroes\Lanager\Lans\LanValidator';

	/**
	 * Presenter class responsible for presenting this model's fields
	 * @var string
	 */
	protected $presenter = 'Zeropingheroes\Lanager\Lans\LanPresenter';

	/**
	 * A single LAN has many user achievements
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function userAchievements()
	{
		return $this->hasMany('Zeropingheroes\Lanager\UserAchievements\UserAchievement');
	}

}