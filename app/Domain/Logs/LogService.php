<?php namespace Zeropingheroes\Lanager\Domain\Logs;

use Zeropingheroes\Lanager\Domain\ResourceService;
use Zeropingheroes\Lanager\Domain\ServiceFilters\FilterableByTimestamps;

class LogService extends ResourceService {

	use FilterableByTimestamps;

	protected $model = 'Zeropingheroes\Lanager\Domain\Logs\Log';

	protected $orderBy = [ ['created_at', 'desc'] ];

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
			$this->addFilter( 'where', 'php_sapi_name', $sapi );
		
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

		$this->addFilter( 'whereIn', 'level', $levelsToShow );
		
		return $this;
	}

}