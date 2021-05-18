<?php

namespace Cerbero\QueryFilters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Request as RequestFacade;
use Illuminate\Support\Facades\Validator;
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
     * @param \Illuminate\Http\Request|null $request
     */
    public function __construct(Request $request = null)
    {
        // do not inject requests to support Laravel Octane
        // @todo: remove constructor in the next major release
    }

    /**
     * Set the request that query filters are based on
     *
     * @param \Illuminate\Http\Request $request
     * @return self
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Retrieve the request that query filters are based on
     *
     * @return \Illuminate\Http\Request
     */
    public function getRequest()
    {
        if (!isset($this->request)) {
            $this->request = RequestFacade::instance();
        }

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

        return (new static())->setRequest($request);
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

        foreach ($this->getRequest()->all() as $filter => $value) {
            if ($this->filterCanBeApplied($filter, $value)) {
                call_user_func([$this, Str::camel($filter)], $value);
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
        $method = Str::camel($filter);

        // do not apply query filters that haven't been implemented
        if (!method_exists($this, $method)) {
            return false;
        }

        // apply query filters with valid values
        if ($value !== '' && $value !== null) {
            $data = $this->getRequest()->only($filter);
            $rules = Arr::only($this->getRules(), $filter);

            return !Validator::make($data, $rules)->fails();
        }

        // apply query filters that don't need values (implicit filters)
        return (new ReflectionMethod($this, $method))->getNumberOfParameters() === 0;
    }

    /**
     * Retrieve the rules to validate filters value.
     * If a filter validation fails, the filter is not applied.
     *
     * @return array
     */
    protected function getRules()
    {
        return [];
    }
}
