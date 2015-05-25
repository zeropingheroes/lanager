<?php namespace Zeropingheroes\Lanager\Domain\Logs;

use Zeropingheroes\Lanager\Domain\ResourceService;

class LogService extends ResourceService {

	protected $orderBy = [ ['created_at', 'desc'] ];

	public function __construct()
	{
		parent::__construct( new Log );
	}

	protected function readAuthorised()
	{
		return $this->user->hasRole('Super Admin');
	}

	/**
	 * Filter log entries by SAPI
	 * @param  string $sapi
	 */
	public function filterBySapi( $sapi )
	{
		$validSapis = [ 'cli', 'cli-server', 'fpm-fcgi', 'apache', 'apache2handler', 'cgi-fcgi' ];

		if ( in_array( $sapi, $validSapis ) )
			$this->model = $this->model->where( 'php_sapi_name', $sapi );
		
		return $this;
	}

	/**
	 * Filter log entries by their level
	 * @param  string $minimumLevel
	 * @return self
	 */
	public function filterByMinimumLevel( $minimumLevel )
	{
		$levels = [ 'debug', 'info', 'notice', 'warning', 'error', 'critical', 'alert', 'emergency' ];

		$levelsToShow = array_slice( $levels, array_search($minimumLevel, $levels) );

		$this->model = $this->model->whereIn( 'level', $levelsToShow );
		
		return $this;
	}

}