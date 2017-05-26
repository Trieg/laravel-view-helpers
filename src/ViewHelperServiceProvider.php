<?php

/**
 * This file is part of the Laravel View Helpers package.
 *
 * @website http://cubes.rs/
 * @author  Djordje Stojiljkovic <djordje.stojiljkovic@cubes.rs>
 *
 */
namespace Cubes\View\Helpers;

use Illuminate\Support\ServiceProvider;
use View\Helpers\Console\Commands\HelperCommand;
use View\Helpers\Helpers\HelperResolver;

class ViewHelperServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Config/helpers.php' => config_path('helpers.php'),
        ], 'config');

        // Resolve all helpers as blade-directives or global closures
        HelperResolver::resolve();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            HelperCommand::class
        ]);
    }
}
