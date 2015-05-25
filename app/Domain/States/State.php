<?php namespace Zeropingheroes\Lanager\Domain\States;

use Zeropingheroes\Lanager\Domain\BaseModel;
use Laracasts\Presenter\PresentableTrait;

class State extends BaseModel {

	use PresentableTrait;

	protected $presenter = 'Zeropingheroes\Lanager\Domain\States\StatePresenter';

	protected $fillable = ['user_id', 'application_id', 'server_id', 'status'];

	protected $nullable = ['application_id', 'server_id'];

	/**
	 * A single state belongs to a single user
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function user()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Domain\Users\User');
	}

	/**
	 * A single state belongs to a single application
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function application()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Domain\Applications\Application');
	}

	/**
	 * A single state belongs to a single server
	 * @return object Illuminate\Database\Eloquent\Relations\Relation
	 */
	public function server()
	{
		return $this->belongsTo('Zeropingheroes\Lanager\Domain\Servers\Server');
	}

}