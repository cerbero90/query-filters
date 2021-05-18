<?php

namespace Cerbero\QueryFilters;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\MySqlConnection;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mockery;
use Orchestra\Testbench\TestCase;

/**
 * The dummy query filters test.
 *
 */
class DummyQueryFiltersTest extends TestCase
{
    /**
     * @test
     */
    public function hydratesQueryFiltersFromPlainArray()
    {
        $array = [
            'won_oscar' => null,
            'acting' => '0',
            'acted-in' => '2000',
        ];

        $queryFilters = DummyQueryFilters::hydrate($array);

        $this->assertInstanceOf(DummyQueryFilters::class, $queryFilters);
        $this->assertSame($array, $queryFilters->getRequest()->query->all());
    }
    /**
     * @test
     */
    public function appliesFilters()
    {
        $request = new Request([
            'won_oscar' => null,
            'acting' => '0',
            'acted-in' => '2000',
        ]);

        $expected = 'select * where `oscars` > 0 and `acting` = 0 and year(`started_acting_at`) <= 2000 ' .
            'and year(`finished_acting_at`) >= 2000';
        $actual = $this->getSqlAfterApplyingFiltersFromRequest($request);

        $this->assertSame($expected, $actual);
    }

    /**
     * Retrieve the SQL statement after query filters are applied based on the given request
     *
     * @param \Illuminate\Http\Request $request
     * @param string $filtersClass
     * @return string
     */
    private function getSqlAfterApplyingFiltersFromRequest(Request $request, $filtersClass = null)
    {
        $pdo = Mockery::mock('PDO');
        $queryBuilder = new QueryBuilder(new MySqlConnection($pdo));
        $eloquentBuilder = new EloquentBuilder($queryBuilder);
        $filtersClass = $filtersClass ?: DummyQueryFilters::class;

        (new $filtersClass())->setRequest($request)->applyToQuery($eloquentBuilder);

        return Str::replaceArray('?', $queryBuilder->getBindings(), $queryBuilder->toSql());
    }

    /**
     * @test
     */
    public function doesNotApplyUnknownFilters()
    {
        $request = new Request([
            'won_oscar' => null,
            'acting' => '0',
            'acted-in' => '2000',
            'filter_not_implemented' => 'foo', // this filter is not implemented, won't be applied
        ]);

        $expected = 'select * where `oscars` > 0 and `acting` = 0 and year(`started_acting_at`) <= 2000 ' .
            'and year(`finished_acting_at`) >= 2000';
        $actual = $this->getSqlAfterApplyingFiltersFromRequest($request);

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function doesNotApplyExplicitFiltersWithNoValue()
    {
        $request = new Request([
            'won_oscar' => null, // implicit filter (does not need a value), will be applied
            'acting' => null, // should have a value, won't be applied
            'acted-in' => '', // should have a value, won't be applied
        ]);

        $expected = 'select * where `oscars` > 0';
        $actual = $this->getSqlAfterApplyingFiltersFromRequest($request);

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function doesNotApplyFiltersWithInvalidValue()
    {
        $request = new Request([
            'won_oscar' => null, // implicit filter (does not need a value), will be applied
            'acting' => 'abc', // should be a boolean, won't be applied
            'acted-in' => 2000, // the year is valid, will be applied
        ]);

        $expected = 'select * where `oscars` > 0 and year(`started_acting_at`) <= 2000 ' .
            'and year(`finished_acting_at`) >= 2000';
        $actual = $this->getSqlAfterApplyingFiltersFromRequest($request, ValidatedDummyQueryFilters::class);

        $this->assertSame($expected, $actual);
    }

    /**
     * @test
     */
    public function retrievesFreshRequestIfNoneIsSet()
    {
        $filters = new DummyQueryFilters();

        $this->assertInstanceOf(Request::class, $filters->getRequest());
    }
}
