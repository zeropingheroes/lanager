<?php namespace Zeropingheroes\Lanager\Domain\Servers;

use Zeropingheroes\Lanager\Domain\BaseModel;
use Laracasts\Presenter\PresentableTrait;

class Server extends BaseModel {

	use PresentableTrait;

	/**
	 * Fields that can be mass assigned
	 * @var array
	 */
	protected $fillable = ['application_id', 'name', 'address', 'port', 'pinned'];

	/**
	 * Fields that can be set to null in the database, if they are not specified when creating a new model
	 * @var array
	 */
	protected $nullable = ['name'];

	/**
	 * Presenter class responsible for presenting this model's fields
	 * @var string
	 */
	protected $presenter = 'Zeropingheroes\Lanager\Domain\Servers\ServerPresenter';

	/**
	 * A single server belongs to a single application
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function application()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Domain\Applications\Application');
	}

	/**
	 * A single server has many states
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function states()
	{
		return $this->hasMany('Zeropingheroes\Lanager\Domain\States\State');
	}

}