<?php

namespace Cerbero\QueryFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use ReflectionMethod;

/**
 * Abstract implementation of a query filters class.
 *
 */
abstract class QueryFilters
{
    /**
     * The HTTP request with query parameters.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The Eloquent query builder.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * Set the dependencies.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Retrieve the request that query filters are based on
     *
     * @return \Illuminate\Http\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Hydrate query filters from a plain array.
     *
     * @param array $queries
     * @return static
     */
    public static function hydrate(array $queries)
    {
        $request = new Request($queries);

        return new static($request);
    }

    /**
     * Apply filters based on query parameters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function applyToQuery(Builder $query)
    {
        $this->query = $query;

        foreach ($this->request->all() as $filter => $value) {
            $method = Str::camel($filter);

            if ($this->filterCanBeApplied($method, $value)) {
                call_user_func([$this, $method], $value);
            }
        }

        return $query;
    }

    /**
     * Determine whether the given filter can be applied with the provided value.
     *
     * @param string $filter
     * @param mixed $value
     * @return boolean
     */
    protected function filterCanBeApplied($filter, $value)
    {
        // do not apply query filters that haven't been implemented
        if (!method_exists($this, $filter)) {
            return false;
        }

        // apply query filters with valid values
        if ($value !== '' && $value !== null) {
            return true;
        }

        // apply query filters that don't need values (implicit filters)
        return (new ReflectionMethod($this, $filter))->getNumberOfParameters() === 0;
    }
}
