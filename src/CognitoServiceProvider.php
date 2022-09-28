<?php

namespace Iwouldrathercode\Cognito;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Aws\CognitoIdentityProvider\CognitoIdentityProviderClient;

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
            ->hasRoute('api')
            ->hasMigration('create_cognito_table')
            ->hasInstallCommand(function(InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations();
            });
    }

    public function packageRegistered()
    {
        $this->app->bind('CognitoClient', function($app) {
            return new CognitoIdentityProviderClient([
                'version' => config('cognito.version'),
                'region' => config('cognito.region'),
            ]);
        });
    }
}
