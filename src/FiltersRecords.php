<?php

namespace Cerbero\QueryFilters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait for Eloquent models to filter records based on query parameters.
 *
 */
trait FiltersRecords
{
    /**
     * Filter records based on the given query filters
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Cerbero\QueryFilters\QueryFilters $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterBy(Builder $query, QueryFilters $filters)
    {
        return $filters->applyToQuery($query);
    }
}
