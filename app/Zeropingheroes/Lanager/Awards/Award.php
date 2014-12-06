<?php namespace Zeropingheroes\Lanager\Awards;

use Zeropingheroes\Lanager\BaseModel;

class Award extends BaseModel {

	protected $fillable = ['user_id', 'achievement_id', 'lan_id'];

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