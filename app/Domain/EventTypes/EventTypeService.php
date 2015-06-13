<?php namespace Zeropingheroes\Lanager\Domain\EventTypes;

use Zeropingheroes\Lanager\Domain\ResourceService;

class EventTypeService extends ResourceService {

	protected $orderBy = [ 'name' ];

	public function __construct()
	{
		parent::__construct(
			new EventType
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

	protected function validationRulesOnStore( $input )
	{
		return [
			'name'			=> 'required|max:255|unique:event_types,name',
			'colour'		=> 'max:255',
		];
	}

	protected function validationRulesOnUpdate( $input )
	{
		$rules = $this->validationRulesOnStore( $input );

		// Exclude current event type from uniqueness test
		$rules['name'] = [ 'required', 'max:255', 'unique:event_types,name,' . $input['id'] ];

		return $rules;
	}
}