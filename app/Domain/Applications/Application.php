<?php namespace Zeropingheroes\Lanager\Domain\Applications;

use Zeropingheroes\Lanager\Domain\BaseModel;
use Laracasts\Presenter\PresentableTrait;

class Application extends BaseModel {
	
	use PresentableTrait;

	protected $presenter = 'Zeropingheroes\Lanager\Domain\Applications\ApplicationPresenter';

	protected $fillable = ['name', 'steam_app_id'];

	/**
	 * A single application has many states
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function states()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Domain\States\State');
	}

	/**
	 * A single application has many servers
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function servers()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Domain\Servers\Server');
	}

}