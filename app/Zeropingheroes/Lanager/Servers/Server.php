<?php namespace Zeropingheroes\Lanager\Servers;

use Zeropingheroes\Lanager\BaseModel;
use Laracasts\Presenter\PresentableTrait;

class Server extends BaseModel {

	protected $fillable = ['application_id', 'name', 'address', 'port', 'pinned'];

	use PresentableTrait;

	protected $presenter = 'Zeropingheroes\Lanager\Servers\ServerPresenter';

	public function application()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Applications\Application');
	}
	
	public function states()
	{
		return $this->hasMany('Zeropingheroes\Lanager\States\State');
	}

}