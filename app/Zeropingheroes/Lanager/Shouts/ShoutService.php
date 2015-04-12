<?php namespace Zeropingheroes\Lanager\Shouts;

use Zeropingheroes\Lanager\FlatResourceService;
use Auth, Authority;

class ShoutService extends FlatResourceService {

	/**
	 * The canonical application-wide name for the resource that this service provides for
	 * @var string
	 */
	protected $resource = 'shouts';

	/**
	 * Instantiate the service with a listener that the service can call methods
	 * on after action success/failure
	 * @param object ResourceServiceListenerContract $listener Listener class with required methods
	 */
	public function __construct( $listener )
	{
		parent::__construct($listener, new Shout);
	}

	/**
	 * Filter user input for data integrity and security
	 * @param  array $input raw input from user
	 * @return array $input input, filtered
	 */
	public function filterInput($input)
	{
		if( Authority::cannot('manage', 'shouts') )
		{
			$input['user_id'] = Auth::user()->id;
			unset($input['pinned']);
		}
		
		return $input;
	}

	/**
	 * Store the resource (with additional processing to standard service method)
	 * @param  array  $ids   list of ids of parent models
	 * @param  array  $input raw input from user
	 */
	public function store($input)
	{
		if( ! array_key_exists('user_id', $input) ) $input['user_id'] = Auth::user()->id;
		
		$input = $this->filterInput($input);
		return parent::store($input);
	}

	/**
	 * Update the resource (with additional processing to standard service method)
	 * @param  array  $ids   list of ids of parent models
	 * @param  array  $input raw input from user
	 */
	public function update($id, $input)
	{
		$input = $this->filterInput($input);
		return parent::update($id, $input);
	}

}