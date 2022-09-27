<?php

namespace DeveloperUnijaya\RmsSpid\Providers;

use Illuminate\Support\ServiceProvider;

class RmsSpidProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }
}
