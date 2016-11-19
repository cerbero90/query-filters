<?php

namespace Cerbero\QueryFilters;

use Illuminate\Console\GeneratorCommand;

/**
 * Artisan command to create query filters.
 *
 * @author    Andrea Marco Sartori
 */
class MakeQueryFiltersCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:query-filters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new query filters class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Query filters';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/query_filters.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        if ($path = config('query_filters.path')) {
            return $rootNamespace . '\\' . str_replace('/', '\\', $path);
        }

        return $rootNamespace;
    }
}
