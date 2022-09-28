<?php

namespace Iwouldrathercode\Cognito;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Iwouldrathercode\Cognito\Commands\CognitoCommand;

class CognitoServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('cognito')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_cognito_table')
            ->hasCommand(CognitoCommand::class);
    }
}
