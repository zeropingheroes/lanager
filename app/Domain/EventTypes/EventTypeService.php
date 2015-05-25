<?php namespace Zeropingheroes\Lanager\Domain\EventTypes;

use Zeropingheroes\Lanager\Domain\ResourceService;

class EventTypeService extends ResourceService {

	protected $orderBy = [ 'name' ];

	public function __construct()
	{
		parent::__construct(
			new EventType,
			new EventTypeValidator
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

}