<?php

namespace Cerbero\QueryFilters;

use Cerbero\QueryFilters\MakeQueryFiltersCommand;
use Illuminate\Support\ServiceProvider;

/**
 * The query filters service provider.
 *
 * @author    Andrea Marco Sartori
 */
class QueryFiltersServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/query_filters.php' => config_path('query_filters.php'),
        ], 'query_filters_config');

        $this->mergeConfigFrom(__DIR__.'/query_filters.php', 'query_filters');

        if ($this->app->runningInConsole()) {
            $this->commands(MakeQueryFiltersCommand::class);
        }
    }
}
