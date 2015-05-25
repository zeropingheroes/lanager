<?php namespace Zeropingheroes\Lanager\Domain\ServiceFilters;

use DateTime;

trait FilterableByCreatedAt {

	/**
	 * Filter created date between two times
	 * @param  DateTime   $start
	 * @param  DateTime   $end  
	 * @return self
	 */
	public function filterCreatedBetween( $start, $end )
	{
		$this->model = $this->model->whereBetween( 'created_at', [ $start, $end ] );
		
		return $this;
	}

	/**
	 * Filter created date after given time
	 * @param  DateTime   $timestamp
	 * @return self
	 */
	public function filterCreatedAfter( $timestamp )
	{
		$this->filterCreatedBetween( $timestamp, new DateTime );
		
		return $this;
	}

}