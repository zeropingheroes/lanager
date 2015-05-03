<?php namespace Zeropingheroes\Lanager\Domain\UserRoles;

use Zeropingheroes\Lanager\Domain\FlatResourceService;
use Auth;

class UserRoleService extends FlatResourceService {

	/**
	 * The canonical application-wide name for the resource that this service provides for
	 * @var string
	 */
	protected $resource = 'user-roles';

	/**
	 * Instantiate the service with a listener that the service can call methods
	 * on after action success/failure
	 * @param object ResourceServiceListenerContract $listener Listener class with required methods
	 */
	public function __construct( $listener )
	{
		parent::__construct($listener, new UserRole);
	}

	/**
	 * Store the resource (with additional processing to standard service method)
	 * @param  array  $ids   list of ids of parent models
	 * @param  array  $input raw input from user
	 */
	public function store($input)
	{
		$input['assigned_by'] = Auth::user()->id;
		
		return parent::store($input);
	}

	/**
	 * Update the resource (with additional processing to standard service method)
	 * @param  array  $ids   list of ids of parent models
	 * @param  array  $input raw input from user
	 */
	public function update( $id, $input)
	{
		$this->errors = 'This resource does not support being updated';
		return $this->listener->updateFailed($this);
	}
}