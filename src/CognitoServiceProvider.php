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
use Illuminate\Contracts\Container\BindingResolutionException;

class CognitoServiceProvider extends PackageServiceProvider
{
    /**
     * 
     * @param Package $package 
     * @return void 
     */
    public function configurePackage(Package $package): void
    {
        /**
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

    /**
     * Will be called after this service provider is registered
     * 
     * @return void 
     */
    public function packageRegistered()
    {
        $this->app->bind('CognitoClient', function($app) {
            return new CognitoIdentityProviderClient([
                'version' => config('cognito.version'),
                'region' => config('cognito.region'),
            ]);
        });
    }

    /**
     * Uses the viaRequest method to validate bearer token and returns App\Models\User object
     * 
     * @return void 
     */
    public function packageBooted()
    {
        Auth::viaRequest('cognito', function (Request $request) {
            if($request->bearerToken()) {

                $bearerToken = $request->bearerToken();
                $poolId = config('cognito.user_pool_id');
                $clientIds = config(('cognito.client_id'));
                $region = config('cognito.region');
                
                $this->downloadCognitoIDPJWK($poolId, $region);

                $token = $this->decodeJWK($poolId, $region, $clientIds, $bearerToken);

                if(empty($token)) {
                    return null;
                }

                return $this->resolveUser($token->getClaim('username'));

            }
            return null;
        });
    }

    /**
     * Downloads the Cognito IDP JWK and stores inside public/storage folder
     * 
     * @param mixed $poolId 
     * @param mixed $region 
     * @return void 
     */
    public function downloadCognitoIDPJWK($poolId, $region)
    {
        $contents = file_get_contents( config('cognito.jwk_idp_url') );
        Storage::disk('public')->put('jwks.json', $contents);
    }

    /**
     * Uses the public/storage/jwks.json to decode with bearer token 
     * and returns token response
     * 
     * @param mixed $poolId 
     * @param mixed $region 
     * @param mixed $clientIds 
     * @param mixed $bearerToken 
     * @return mixed 
     * @throws Exception 
     */
    public function decodeJWK($poolId, $region, $clientIds, $bearerToken)
    {
        $jwk = json_decode(file_get_contents(public_path('storage/jwks.json')), true);
        $pool = new UserPool($poolId, $clientIds, $region, $jwk);
        $verifier = new CognitoClaimVerifier($pool);
        $decoder = new JwtDecoder($verifier);
        return $decoder->decode($bearerToken);
    }

    /**
     * Returns the matching App\Models\User object based on the username
     * 
     * @param mixed $username 
     * @return mixed 
     */
    public function resolveUser($username)
    {
        return User::where('username', $username)->first();
    }
}
