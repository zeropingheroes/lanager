<?php namespace Zeropingheroes\Lanager\Logs;

use Zeropingheroes\Lanager\FlatResourceService;

class LogService extends FlatResourceService {

	/**
	 * The canonical application-wide name for the resource that this service provides for
	 * @var string
	 */
	protected $resource = 'logs';

	/**
	 * Instantiate the service with a listener that the service can call methods
	 * on after action success/failure
	 * @param object ResourceServiceListenerContract $listener Listener class with required methods
	 */
	public function __construct( $listener )
	{
		parent::__construct($listener, new log);
	}

}