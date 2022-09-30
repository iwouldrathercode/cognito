<?php

namespace Iwouldrathercode\Cognito;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Iwouldrathercode\Cognito\Helpers\CognitoJWT;
use Spatie\LaravelPackageTools\Package;
use Iwouldrathercode\Cognito\Commands\SetupCommand;
use Iwouldrathercode\Cognito\Facades\CognitoClient;
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
            ->hasMigration('create_public_keys_table')
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
        Auth::viaRequest('cognito', function (Request $request) {
            // return User::where('cognito_token', $request->bearerToken())->first();
            if($request->bearerToken()) {

                $jwt = $request->bearerToken();
                $region = config('cognito.region');
                $userPoolId = config('cognito.user_pool_id');
                return CognitoJWT::verifyToken($jwt, $region, $userPoolId);

                // TODO 3: If valid, get User from users table

                // TODO 4: If invalid, return null

            }
            return null;
        });
    }
}
