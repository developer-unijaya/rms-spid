<?php

namespace DeveloperUnijaya\RmsSpid;

use DeveloperUnijaya\RmsSpid\Commands\RmsSpidCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class RmsSpidServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name('rms-spid')
            ->hasConfigFile('rms-spid')
            ->hasRoutes(['web', 'api'])
            ->hasMigration('create_user_spid_table')
            ->hasCommand(RmsSpidCommand::class);
    }
}
