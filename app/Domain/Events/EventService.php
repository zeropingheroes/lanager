<?php namespace Zeropingheroes\Lanager\Domain\Events;

use Zeropingheroes\Lanager\Domain\ResourceService;

class EventService extends ResourceService {

	protected $orderBy = [ 'start' ];

	protected $eagerLoad = [ 'type', 'eventSignups', 'eventSignups.user.state.application' ];

	public function __construct()
	{
		parent::__construct(
			new Event,
			new EventValidator
		);
	}

	protected function readAuthorised()
	{
		return true;
	}

	protected function storeAuthorised()
	{
		return $this->user->hasRole('Events Admin');
	}

	protected function updateAuthorised()
	{
		return $this->user->hasRole('Events Admin');
	}

	protected function destroyAuthorised()
	{
		return $this->user->hasRole('Events Admin');
	}

	protected function filter()
	{
		if ( ! $this->user->hasRole( 'Events Admin' ) )
			$this->model = $this->model->where( 'published', true );
	}

}