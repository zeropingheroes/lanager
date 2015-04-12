<?php namespace Zeropingheroes\Lanager\Applications;

use Zeropingheroes\Lanager\BaseModel;
use Laracasts\Presenter\PresentableTrait;

class Application extends BaseModel {
	
	use PresentableTrait;

	/**
	 * Fields that can be mass assigned
	 * @var array
	 */
	protected $fillable = ['name', 'steam_app_id'];

	/**
	 * Presenter class responsible for presenting this model's fields
	 * @var string
	 */
	protected $presenter = 'Zeropingheroes\Lanager\Applications\ApplicationPresenter';

	/**
	 * A single application has many states
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function states()
	{
		return $this->hasMany('Zeropingheroes\Lanager\States\State');
	}

	/**
	 * A single application has many servers
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function servers()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Servers\Server');
	}

}