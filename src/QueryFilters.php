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
     * List of filters not requiring a value.
     *
     * @var array
     */
    protected $implicitFilters = [];

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
     * Hydrate the filters from plain array.
     *
     * @param    array    $queries
     * @return    static
     */
    public static function hydrate(array $queries)
    {
        $request = new Request($queries);

        return new static($request);
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
        $filterExists = method_exists($this, $filter);
        $hasValue = $value !== '' && $value !== null;
        $valueIsLegit = $hasValue || in_array($filter, $this->implicitFilters);

        return $filterExists && $valueIsLegit;
    }
}
