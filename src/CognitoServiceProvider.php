<?php

namespace Iwouldrathercode\Cognito;

use App\Models\User as User;
use Illuminate\Http\Request;
use TeamGantt\Juhwit\JwtDecoder;
use Illuminate\Support\Facades\Auth;
use TeamGantt\Juhwit\Models\UserPool;
use Spatie\LaravelPackageTools\Package;
use Illuminate\Support\Facades\Storage;
use TeamGantt\Juhwit\CognitoClaimVerifier;
use Iwouldrathercode\Cognito\Commands\SetupCommand;
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
            ->hasMigration('modify_users_table')
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

                $bearerToken = $request->bearerToken();

                $poolId = config('cognito.user_pool_id');
                $clientIds = config(('cognito.client_id'));
                $region = config('cognito.region');

                $contents = file_get_contents('https://cognito-idp.'.$region.'.amazonaws.com/'.$poolId.'/.well-known/jwks.json');
                Storage::disk('public')->put('jwks.json', $contents);

                // we need some public keys in the form of a jwk (accessible via cognito)
                $jwk = json_decode(file_get_contents(public_path('storage/jwks.json')), true);

                $pool = new UserPool($poolId, $clientIds, $region, $jwk);
                $verifier = new CognitoClaimVerifier($pool);
                $decoder = new JwtDecoder($verifier);

                $token = $decoder->decode($bearerToken);
                if(empty($token)) {
                    return null;
                }

                return User::where('username', $token->getClaim('username'))->first();

            }
            return null;
        });
    }
}
