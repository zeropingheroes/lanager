<?php namespace Zeropingheroes\Lanager\Domain\EventSignups;

use Zeropingheroes\Lanager\Domain\NestedResourceService;
use Zeropingheroes\Lanager\Domain\Events\Event;

use Auth, Authority;

class EventSignupService extends NestedResourceService {

	/**
	 * The canonical application-wide name for the resource that this service provides for
	 * @var string
	 */
	public $resource = 'events.signups';

	/**
	 * Instantiate the service with a listener that the service can call methods
	 * on after action success/failure
	 * @param object ResourceServiceListenerContract $listener Listener class with required methods
	 */
	public function __construct( $listener )
	{
		$models = [
			new Event,
			new EventSignup,
		];
		parent::__construct($listener, $models);
	}

	/**
	 * Filter user input for data integrity and security
	 * @param  array $input raw input from user
	 * @return array $input input, filtered
	 */
	private function filterInput( $input )
	{
		if( Authority::can('manage', 'events.signups') )
		{
			$input = array_only($input, ['user_id']);

			// allow event managers to sign up other users (or default to their user id)
			if( ! array_key_exists('user_id', $input) )	$input['user_id'] = Auth::user()->id;
		}
		else // only allow standard users to sign themselves up
		{
			$input['user_id'] = Auth::user()->id;
		}
		return $input;
	}

	/**
	 * Store the resource (with additional processing to standard service method)
	 * @param  array  $ids   list of ids of parent models
	 * @param  array  $input raw input from user
	 */
	public function store( array $ids, $input)
	{
		$input = $this->filterInput($input);
		if( ! parent::parent($ids)->isOpenForSignups() )
		{
			$this->errors = 'Event is not open for signups';
			return $this->listener->storeFailed($this);
		}	
		return parent::store($ids, $input);
	}

	/**
	 * Block attempts to update the resource
	 * @param  array  $ids   list of ids of parent models
	 * @param  array  $input raw input from user
	 */
	public function update( array $ids, $input)
	{
		$this->errors = 'This resource does not support being updated';
		return $this->listener->updateFailed($this);
	}
}