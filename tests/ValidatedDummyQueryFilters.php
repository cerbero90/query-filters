<?php

namespace Cerbero\QueryFilters;

/**
 * Filter records based on query parameters.
 *
 */
class ValidatedDummyQueryFilters extends DummyQueryFilters
{
    /**
     * Retrieve the rules to validate filters value.
     * If a filter validation fails, the filter is not applied.
     *
     * @return array
     */
    protected function getRules()
    {
        return [
            'acting' => 'bool',
            'acted-in' => 'int|digits:4',
        ];
    }
}
