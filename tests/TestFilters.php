<?php

namespace Cerbero\QueryFilters;

/**
 * Dummy class to test query filters.
 *
 * @author    Andrea Marco Sartori
 */
class TestFilters extends QueryFilters
{
    /**
     * List of called filters, for testing purposes.
     *
     * @author  Andrea Marco Sartori
     * @var     array
     */
    public $filters = [];

    /**
     * List of filters not requiring a value.
     *
     * @var array
     */
    protected $implicitFilters = [
        'implicit',
    ];

    /**
     * Dummy filter requiring a value.
     *
     * @author    Andrea Marco Sartori
     * @param    mixed    $value
     * @return    void
     */
    public function foo($value)
    {
        $this->filters['foo'] = $value;
    }

    /**
     * Dummy filter requiring a value.
     *
     * @author    Andrea Marco Sartori
     * @param    mixed    $value
     * @return    void
     */
    public function bar($value)
    {
        $this->filters['bar'] = $value;
    }

    /**
     * Dummy filter never called.
     *
     * @author    Andrea Marco Sartori
     * @return    void
     */
    public function baz()
    {
        $this->filters['baz'] = '';
    }

    /**
     * Dummy filter to test query strings with dashes and underscores.
     *
     * @author    Andrea Marco Sartori
     * @param    mixed    $value
     * @return    void
     */
    public function fooBarBaz($value)
    {
        $this->filters['foo-bar_baz'] = $value;
    }

    /**
     * Dummy filter never called.
     *
     * @author    Andrea Marco Sartori
     * @return    void
     */
    public function test()
    {
        $this->filters['test'] = null;
    }

    /**
     * Dummy filter requiring no values.
     *
     * @author    Andrea Marco Sartori
     * @return    void
     */
    public function implicit()
    {
        $this->filters['implicit'] = '';
    }
}
