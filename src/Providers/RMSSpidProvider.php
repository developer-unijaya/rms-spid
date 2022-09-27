<?php

namespace DeveloperUnijaya\RMSSpid\Providers;

use Illuminate\Support\ServiceProvider;

class RMSSpidProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }
}