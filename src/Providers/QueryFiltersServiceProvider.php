<?php

namespace Cerbero\QueryFilters\Providers;

use Cerbero\QueryFilters\Console\Commands\MakeQueryFiltersCommand;
use Illuminate\Support\ServiceProvider;

/**
 * The query filters service provider.
 *
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
        // determine where to publish the configuration, if requested
        $this->publishes([
            __DIR__ . '/../../config/query_filters.php' => config_path('query_filters.php'),
        ], 'query_filters_config');

        // merge the published configuration with the package default one
        $this->mergeConfigFrom(__DIR__ . '/../../config/query_filters.php', 'query_filters');

        // register console command when running Artisan
        if ($this->app->runningInConsole()) {
            $this->commands(MakeQueryFiltersCommand::class);
        }
    }
}
