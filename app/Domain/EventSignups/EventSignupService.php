<?php namespace Zeropingheroes\Lanager\Domain\EventSignups;

use Zeropingheroes\Lanager\Domain\ResourceService;
use Zeropingheroes\Lanager\Domain\Events\EventService;
use Zeropingheroes\Lanager\Domain\AuthorisationException;
use Zeropingheroes\Lanager\Domain\ServiceFilters\FilterableByTimestamps;
use Zeropingheroes\Lanager\Domain\ServiceFilters\FilterableByUser;
use DomainException;

class EventSignupService extends ResourceService {

	use FilterableByTimestamps;

	use FilterableByUser;

	protected $model = 'Zeropingheroes\Lanager\Domain\EventSignups\EventSignup';

	protected $eagerLoad = [ 'user.state.application' ];

	public function store( $input )
	{
		$this->setUser();

		if ( ! isset( $input['user_id'] ) ) $input['user_id'] = $this->user->id();

		return parent::store( $input );
	}

	/**
	 * Filter by a given event
	 * @param  integer $eventId  event's ID
	 * @return self
	 */
	public function filterByEvent( $eventId )
	{
		$this->addFilter( 'where', 'event_id', $eventId );

		return $this;
	}

	protected function readAuthorised()
	{
		return true;
	}

	protected function storeAuthorised()
	{
		return $this->user->isAuthenticated();
	}

	protected function destroyAuthorised()
	{
		return $this->user->isAuthenticated();
	}

	protected function validationRulesOnStore( $input )
	{
		return [
			'event_id'		=> [ 'required', 'exists:events,id' ],
			'user_id'		=> [ 'required', 'exists:users,id' ],
		];
	}

	protected function validationRulesOnUpdate( $input )
	{
		return $this->validationRulesOnStore( $input );
	}

	protected function domainRulesOnStore( $input )
	{
		$event = ( new EventService )->single( $input['event_id'] );

		if ( ! $this->user->hasRole('Events Admin') )
		{
			if ( $input['user_id'] != $this->user->id() )
				throw new AuthorisationException( 'You may only sign yourself up to events' );

			if ( ! $event->isOpenForSignups() )
				throw new DomainException( 'Event is not open for signups' );
		}

		if ( ! $event->allowsSignups() )
			throw new DomainException( 'Event does not allow signups' );

		if ( $event->hasSignupFromUser( $this->user->id() ) )
			throw new DomainException( 'User already signed up to event' );
	}

	protected function domainRulesOnDestroy( $input )
	{
		if ( ! $this->user->hasRole('Events Admin') )
		{
			if ( $input['user_id'] != $this->user->id() )
				throw new AuthorisationException( 'You may only delete your own event signups' );
		}
	}

}
