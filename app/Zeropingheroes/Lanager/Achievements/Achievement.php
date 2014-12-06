<?php namespace Zeropingheroes\Lanager\Achievements;

use Zeropingheroes\Lanager\BaseModel;

class Achievement extends BaseModel {

	protected $fillable = ['name', 'description'];

	public $validator = 'Zeropingheroes\Lanager\Achievements\AchievementValidator';


	public function awards()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Awards\Award');
	}

	public function users()
	{
		return $this->belongsToMany('Zeropingheroes\Lanager\Users\User', 'awards');
	}

}