<?php

namespace Cerbero\QueryFilters;

use Cerbero\QueryFilters\Providers\QueryFiltersServiceProvider;
use Orchestra\Testbench\TestCase;

/**
 * The make query filters command test.
 *
 */
class MakeQueryFiltersCommandTest extends TestCase
{
    /**
     * Retrieve the service providers of the package
     *
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getPackageProviders($app)
    {
        return [QueryFiltersServiceProvider::class];
    }

    /**
     * @test
     */
    public function generatesEmptyQueryFilters()
    {
        $this->artisan('make:query-filters', ['name' => 'Foo']);

        $queryFiltersPath = $this->app->path('QueryFilters/Foo.php');
        $expected = file_get_contents(__DIR__ . '/empty_query_filters.stub');

        $this->assertTrue(file_exists($queryFiltersPath));
        $this->assertSame($expected, file_get_contents($queryFiltersPath));
    }

    /**
     * @test
     */
    public function generatesPopulatedQueryFilters()
    {
        $this->artisan('make:query-filters', [
            'name' => 'Bar',
            'filters' => 'won_oscar&acting=0&acted-in=2000',
        ]);

        $queryFiltersPath = $this->app->path('QueryFilters/Bar.php');
        $expected = file_get_contents(__DIR__ . '/populated_query_filters.stub');

        $this->assertTrue(file_exists($queryFiltersPath));
        $this->assertSame($expected, file_get_contents($queryFiltersPath));
    }

    /**
     * @test
     */
    public function generatesQueryFiltersInDifferentPath()
    {
        $this->app['config']->set('query_filters.path', 'Hello/World');
        $this->artisan('make:query-filters', ['name' => 'Baz']);

        $queryFiltersPath = $this->app->path('Hello/World/Baz.php');
        $expected = file_get_contents(__DIR__ . '/query_filters_in_different_path.stub');

        $this->assertTrue(file_exists($queryFiltersPath));
        $this->assertSame($expected, file_get_contents($queryFiltersPath));
    }
}
