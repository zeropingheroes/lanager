<?php namespace Zeropingheroes\Lanager\States;

use Zeropingheroes\Lanager\BaseModel;
use Laracasts\Presenter\PresentableTrait;

class State extends BaseModel {
	
	protected $fillable = ['user_id', 'application_id', 'server_id', 'status'];
	protected $nullable = ['application_id', 'server_id'];

	use PresentableTrait;

	protected $presenter = 'Zeropingheroes\Lanager\States\StatePresenter';

	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Users\User');
	}

	public function application()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Applications\Application');
	}

	public function server()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Servers\Server');
	}

	/**
	 * Translate the status code to English
	 *
	 * @return string
	 */
	public function getStatus()
	{
		switch ($this->status)
		{
			case '1':
				if( !empty($this->application_id) ) return 'In Game'; // Online AND In-game != Online AND away
				return 'Online';
			case '2':
				return 'Busy';
			case '3':
				return 'Away';
			case '4':
				return 'Snooze';
			case '5':
				return 'Looking to trade';
			case '6':
				return 'Looking to play';
			case '0':
			default:
			return 'Offline';
		}
	}

	/**
	 * Get the most recent Steam state
	 *
	 * @return Query
	 */
	public function scopeLatest($query)
	{
		return $query->orderBy('created_at', 'desc')
					->where('created_at', '>=', date('Y-m-d H:i:s',time()-300)); // no states older than 5 mins
	}

}