<?php

namespace Q00n\QLogger;

use Illuminate\Support\ServiceProvider;

class QLoggerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/qlogging.php' => config_path('qlogging.php')
        ], 'qlogger-config');

        $this->loadRoutesFrom(__DIR__.'/../routes/routes.php');
        $this->loadViewsFrom(__DIR__.'/../views', 'qlogger');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/qlogging.php', 'qlogging'
        );

        $this->app->bind('q00n-qlogger', function() {
            return new LogWriter;
        });
    }
}
