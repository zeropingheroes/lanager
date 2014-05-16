<?php namespace Zeropingheroes\Lanager\Models;

class Award extends BaseModel {

	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Models\User');
	}

	public function achievement()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Models\Achievement');
	}

	public function lan()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Models\Lan');
	}

}