<?php namespace Zeropingheroes\Lanager\Awards;

use Zeropingheroes\Lanager\BaseModel;

class Award extends BaseModel {

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