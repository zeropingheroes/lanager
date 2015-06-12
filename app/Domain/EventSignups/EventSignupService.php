<?php namespace Zeropingheroes\Lanager\Domain\EventSignups;

use Zeropingheroes\Lanager\Domain\ResourceService;
use Zeropingheroes\Lanager\Domain\Events\EventService;
use Zeropingheroes\Lanager\Domain\AuthorisationException;
use DomainException;

class EventSignupService extends ResourceService {

	protected $eagerLoad = [ 'user.state.application' ];

	public function __construct()
	{
		parent::__construct(
			new EventSignup,
			new EventSignupValidator
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

	/**
	 * Filter by a given user
	 * @param  integer $userId  user's ID
	 * @return self
	 */
	public function filterByUser( $userId )
	{
		$this->model = $this->model->where( 'user_id', $userId );

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

	protected function rulesOnStore( $input )
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

	protected function rulesOnDestroy( $input )
	{
		if ( ! $this->user->hasRole('Events Admin') )
		{
			if ( $input['user_id'] != $this->user->id() )
				throw new AuthorisationException( 'You may only delete your own event signups' );
		}
	}

}