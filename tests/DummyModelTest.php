<?php

namespace Cerbero\QueryFilters;

use Illuminate\Database\MySqlConnection;
use Illuminate\Support\Str;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * The dummy model test.
 *
 */
class DummyModelTest extends TestCase
{
    /**
     * @test
     */
    public function filtersRecordsBasedOnQueryFilters()
    {
        $queryFilters = DummyQueryFilters::hydrate([
            'won_oscar' => null,
            'acting' => '0',
            'acted-in' => '2000',
        ]);

        $pdo = Mockery::mock('PDO');
        $connection = new MySqlConnection($pdo);

        $model = Mockery::mock(DummyModel::class)
            ->makePartial()
            ->expects()
            ->getConnection()
            ->andReturns($connection)
            ->getMock();

        $queryBuilder = $model->filterBy($queryFilters)->getQuery();
        $expected = 'select * from `dummy_table` where `oscars` > 0 and `acting` = 0 and year(`started_acting_at`) <= 2000 and year(`finished_acting_at`) >= 2000';
        $actual = Str::replaceArray('?', $queryBuilder->getBindings(), $queryBuilder->toSql());

        $this->assertSame($expected, $actual);
    }
}
