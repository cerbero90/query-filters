<?php

namespace Cerbero\QueryFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Abstract implementation of a query filters applier.
 *
 */
abstract class QueryFilters
{
    /**
     * The current HTTP request.
     *
     * @var Illuminate\Http\Request
     */
    protected $request;

    /**
     * Eloquent query builder.
     *
     * @var     Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * Set the dependencies.
     *
     * @param    Request    $request
     * @return    void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply all the filters to the given query.
     *
     * @param    Illuminate\Database\Eloquent\Builder    $query
     * @return    Illuminate\Database\Eloquent\Builder
     */
    public function applyToQuery(Builder $query)
    {
        $this->query = $query;

        foreach ($this->request->all() as $filter => $value) {
            $method = Str::camel($filter);

            if (method_exists($this, $method)) {
                call_user_func([$this, $method], $value);
            }
        }

        return $query;
    }
}
