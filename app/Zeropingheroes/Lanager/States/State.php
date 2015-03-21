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

}