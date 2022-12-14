<?php

namespace DeveloperUnijaya\RmsSpid;

use DeveloperUnijaya\RmsSpid\Commands\SpidClearLogCommand;
use DeveloperUnijaya\RmsSpid\Commands\SpidResetExpiredTokenCommand;
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
            ->hasRoutes(['web', 'api'])
            ->hasViews('RmsSpidView')
            ->hasMigration('create_user_spid_table')
            ->hasCommands([
                SpidClearLogCommand::class,
                SpidResetExpiredTokenCommand::class,
            ])
            ->hasConfigFile('rms-spid');
    }
}
