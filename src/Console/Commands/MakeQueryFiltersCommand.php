<?php

namespace Cerbero\QueryFilters\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Artisan command to generate query filters.
 *
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
        return __DIR__ . '/../../../stubs/query_filters.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        // get query filters path from configuration
        $path = Config::get('query_filters.path');
        // ensure the path always starts with "app/"
        $path = Str::start(ltrim($path, '/'), 'app/');
        // remove "app/" from the beginning of the path
        $path = preg_replace('#^app\/#', '', $path);
        // convert the path into namespace
        $namespace = implode('\\', array_map('ucfirst', explode('/', $path)));
        // prepend the root namespace
        return $rootNamespace . '\\' . $namespace;
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);

        return $this->replaceFilters($stub);
    }

    /**
     * Replace filters for the given stub
     *
     * @param string $stub
     * @return string
     */
    protected function replaceFilters($stub)
    {
        parse_str($this->argument('filters'), $rawFilters);

        if (empty($rawFilters)) {
            return str_replace('DummyFilters', PHP_EOL . '    //' . PHP_EOL, $stub);
        }

        $filters = '';
        $filterStub = file_get_contents(__DIR__ . '/../../../stubs/filter.stub');

        foreach ($rawFilters as $queryParameter => $parameterName) {
            $filterName = Str::camel($queryParameter);
            $parameterVariable = $parameterName === '' ? '' : '$' . $parameterName;
            $parameterDoc = $parameterName === '' ? '' : '@param mixed dummyParameter' . PHP_EOL . '     * ';
            $search = ['dummyQueryParameter', 'dummyParameterDoc', 'dummyFilter', 'dummyParameter'];
            $replace = [$queryParameter, $parameterDoc, $filterName, $parameterVariable];
            $filters .= str_replace($search, $replace, $filterStub);
        }

        return str_replace('DummyFilters', $filters, $stub);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the class'],
            ['filters', InputArgument::OPTIONAL, "The name of the filters e.g. 'won_oscar&acting=bool&acted-in=year'"],
        ];
    }
}
