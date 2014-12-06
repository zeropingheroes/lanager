<?php namespace Zeropingheroes\Lanager\Shouts;

use Zeropingheroes\Lanager\BaseModel;

class Shout extends BaseModel {

	protected $fillable = ['content', 'pinned'];

	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Users\User');
	}

}