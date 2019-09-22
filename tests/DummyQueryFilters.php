<?php

namespace Cerbero\QueryFilters;

use Cerbero\QueryFilters\QueryFilters;

/**
 * Filter records based on query parameters.
 *
 */
class DummyQueryFilters extends QueryFilters
{
    /**
     * Filter records based on the query parameter "won_oscar"
     * 
     * @return void
     */
    public function wonOscar()
    {
        $this->query->where('oscars', '>', 0);
    }

    /**
     * Filter records based on the query parameter "acting"
     * 
     * @param mixed $bool
     * @return void
     */
    public function acting($bool)
    {
        $this->query->whereActing($bool);
    }

    /**
     * Filter records based on the query parameter "acted-in"
     * 
     * @param mixed $year
     * @return void
     */
    public function actedIn($year)
    {
        $this->query
            ->whereYear('started_acting_at', '<=', $year)
            ->whereYear('finished_acting_at', '>=', $year);
    }
}
