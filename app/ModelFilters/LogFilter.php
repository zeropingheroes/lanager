<?php namespace Zeropingheroes\Lanager\ModelFilters;

use EloquentFilter\ModelFilter;

class LogFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function level($level)
    {
        return $this->where('level', $level);
    }
}
