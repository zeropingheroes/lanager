<?php namespace Zeropingheroes\Lanager\Shouts;

use Zeropingheroes\Lanager\BaseModel;

class Shout extends BaseModel {

	public static $rules = array(
		'content'		=> 'required|max:140|flood_protect:shouts',
	);

	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Users\User');
	}

}