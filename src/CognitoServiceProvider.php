<?php

namespace Iwouldrathercode\Cognito;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\LaravelPackageTools\Package;
use Iwouldrathercode\Cognito\Guards\CognitoGuard;
use Iwouldrathercode\Cognito\Commands\SetupCommand;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Iwouldrathercode\Cognito\Providers\CognitoUserProvider;
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
            ->hasMigration('create_cognito_table')
            ->hasCommands([
                SetupCommand::class
            ])
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

    public function packageBooted()
    {

//        Auth::extend('cognito', function ($app) {
//            $request = $app->request;
//            $userProvider = app(CognitoUserProvider::class);
//            return new CognitoGuard($userProvider, $request);
//        });
    }
}
