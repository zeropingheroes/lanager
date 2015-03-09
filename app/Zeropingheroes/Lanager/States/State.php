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