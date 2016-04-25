<?php

namespace Cerbero\QueryFilters;

/**
 * Trait to let Eloquent models filter their records based on the request query string.
 *
 */
trait FiltersRecords
{
    /**
     * Query scope to filter records based on the query string.
     *
     * @param    Illuminate\Database\Eloquent\Builder    $query
     * @param    Cerbero\QueryFilters\QueryFilters    $filters
     * @return    Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterBy($query, QueryFilters $filters)
    {
        return $filters->applyToQuery($query);
    }
}
