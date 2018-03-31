<?php namespace Zeropingheroes\Lanager\Domain\ServiceFilters;

use DateTime;

trait FilterableByTimestamps
{

    /**
     * Filter created date between two times
     * @param  DateTime $start
     * @param  DateTime $end
     * @return self
     */
    public function filterCreatedBetween($start, $end)
    {
        $this->addFilter('whereBetween', 'created_at', [$start, $end]);

        return $this;
    }

    /**
     * Filter created date after given time
     * @param  DateTime $timestamp
     * @return self
     */
    public function filterCreatedAfter($timestamp)
    {
        $this->filterCreatedBetween($timestamp, new DateTime);

        return $this;
    }

}