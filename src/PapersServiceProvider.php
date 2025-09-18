<?php

namespace Kiwilan\Papers;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Kiwilan\Papers\Commands\PapersCommand;

class PapersServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('papers-for-laravel')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_papers_for_laravel_table')
            ->hasCommand(PapersCommand::class);
    }
}
