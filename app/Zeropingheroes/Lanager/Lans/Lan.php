<?php namespace Zeropingheroes\Lanager\Lans;

use Zeropingheroes\Lanager\BaseModel;
use Laracasts\Presenter\PresentableTrait;

class Lan extends BaseModel {

	protected $fillable = ['name', 'start', 'end'];

	public $validator = 'Zeropingheroes\Lanager\Lans\LanValidator';

	use PresentableTrait;

	protected $presenter = 'Zeropingheroes\Lanager\Lans\LanPresenter';

	public function userAchievements()
	{
		return $this->hasMany('Zeropingheroes\Lanager\UserAchievements\UserAchievement');
	}

}