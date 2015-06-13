<?php namespace Zeropingheroes\Lanager\Domain\EventSignups;

use Zeropingheroes\Lanager\Domain\ResourceService;
use Zeropingheroes\Lanager\Domain\Events\EventService;
use Zeropingheroes\Lanager\Domain\AuthorisationException;
use Zeropingheroes\Lanager\Domain\ServiceFilters\FilterableByCreatedAt;
use Zeropingheroes\Lanager\Domain\ServiceFilters\FilterableByUser;
use DomainException;

class EventSignupService extends ResourceService {

	protected $eagerLoad = [ 'user.state.application' ];

	use FilterableByCreatedAt;

	use FilterableByUser;

	public function __construct()
	{
		parent::__construct(
			new EventSignup
		);
	}

	public function store( $input )
	{
		if ( ! isset( $input['user_id'] ) ) $input['user_id'] = $this->user->id();

		parent::store( $input );
	}

	/**
	 * Filter by a given event
	 * @param  integer $eventId  event's ID
	 * @return self
	 */
	public function filterByEvent( $eventId )
	{
		$this->model = $this->model->where( 'event_id', $eventId );

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

	protected function domainRulesOnStore( $input )
	{
		$event = ( new EventService )->single( $input['event_id'] );

		if ( ! $this->user->hasRole('Events Admin') )
		{
			if ( $this->input['user_id'] != $this->user->id() )
				throw new AuthorisationException( 'You may only sign yourself up to events' );

			if ( ! $event->isOpenForSignups() )
				throw new DomainException( 'Event is not open for signups' );
		}

		if ( ! $event->allowsSignups() )
			throw new DomainException( 'Event does not allow signups' );

		if ( $event->hasSignupFromUser( $this->model->user_id ) )
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

}