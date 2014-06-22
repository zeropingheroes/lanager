<?php namespace Zeropingheroes\Lanager;

use LaravelBook\Ardent\Ardent;
use Zeropingheroes\Lanager\Users\User;

class BaseModel extends Ardent {

	public function scopeOnlyVisibleUsers($query)
	{
		$query->whereIn('user_id', function($query)
		{
			$query->select('id')
					->from(with(new User)->getTable())
					->where('visible', 1);
		});
	}

}