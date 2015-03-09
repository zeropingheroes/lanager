<?php namespace Zeropingheroes\Lanager\Applications;

use Zeropingheroes\Lanager\BaseModel;
use Laracasts\Presenter\PresentableTrait;

class Application extends BaseModel {

	protected $fillable = ['name', 'steam_app_id'];

	use PresentableTrait;

	protected $presenter = 'Zeropingheroes\Lanager\Applications\ApplicationPresenter';

	public function states()
	{
		return $this->hasMany('Zeropingheroes\Lanager\States\State');
	}

	public function servers()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Servers\Server');
	}

}