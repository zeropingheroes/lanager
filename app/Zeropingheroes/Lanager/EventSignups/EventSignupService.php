<?php namespace Zeropingheroes\Lanager\EventSignups;

use Zeropingheroes\Lanager\NestedResourceService;
use Zeropingheroes\Lanager\Events\Event;

use Auth, Authority;

class EventSignupService extends NestedResourceService {

	public $resource = 'event signup';

	public function __construct( $listener )
	{
		$models = [
			new Event,
			new EventSignup,
		];
		parent::__construct($listener, $models);
	}

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

	public function store( array $ids, $input)
	{
		$input = $this->filterInput($input);
		return parent::store($ids, $input);
	}

	public function update( array $ids, $input)
	{
		$this->errors = 'This resource does not support being updated';
		return $this->listener->updateFailed($this);
	}
}