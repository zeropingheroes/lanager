<?php namespace Zeropingheroes\Lanager\Domain\EventTypes;

use Zeropingheroes\Lanager\Domain\ResourceService;
use Zeropingheroes\Lanager\Domain\Events\EventService;
use \DomainException;

class EventTypeService extends ResourceService {

	protected $model = 'Zeropingheroes\Lanager\Domain\EventTypes\EventType';

	protected $orderBy = [ 'name' ];

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
			'name'			=> [ 'required', 'max:255', 'unique:event_types,name' ],
			'colour'		=> [ 'max:255' ],
		];
	}

	protected function validationRulesOnUpdate( $input )
	{
		$rules = $this->validationRulesOnStore( $input );

		// Exclude current event type from uniqueness test
		$rules['name'] = [ 'required', 'max:255', 'unique:event_types,name,' . $input['id'] ];

		return $rules;
	}

	protected function domainRulesOnDestroy( $input )
	{
		$events = ( new EventService )->filterByEventType( $input['id'] )->all();
			if ( $events->count() > 0 )
				throw new DomainException( 'This event type is in use by events - please edit them before deleting this event type' );
	}

}