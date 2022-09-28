<?php

namespace DeveloperUnijaya\RmsSpid\Providers;

use DeveloperUnijaya\RmsSpid\RmsSpid;
use Illuminate\Support\ServiceProvider;

class RmsSpidProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'spid');

        $this->app->singleton(RmsSpid::class, function ($app) {
            return new RmsSpid();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('spid.php'),
            ], 'config');

        }
    }
}
