<?php

namespace Iwouldrathercode\Cognito\Providers;

use App\Models\User;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Iwouldrathercode\Cognito\Facades\CognitoClient;
use Iwouldrathercode\Cognito\Exceptions\CognitoException;
use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;

class CognitoUserProvider implements UserProvider
{
    /**
     * @param $identifier
     * @return Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        // TODO: Implement retrieveById() method.
    }

    /**
     * @param $identifier
     * @param $token
     * @return Authenticatable|null
     * @throws CognitoException
     */
    public function retrieveByToken($identifier, $token)
    {
        if(empty($token)) {
            return false;
        }

        try {
            $response = CognitoClient::getUser([
                $identifier => $token
            ]);
        } catch (CognitoIdentityProviderException $exception) {
            return throw new CognitoException($exception);
        }
        // return new User($response->toArray());
        return $response->toArray();
    }

    /**
     * @param Authenticatable $user
     * @param $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        // TODO: Implement updateRememberToken() method.
    }

    /**
     * @param array $credentials
     * @return Authenticatable|null
     * @throws CognitoException
     */
    public function retrieveByCredentials(array $credentials)
    {
        try {
            $response = CognitoClient::adminInitiateAuth([
                'AuthFlow' => config('cognito.auth_flow'),
                'ClientId' => config('cognito.client_id'),
                'UserPoolId' => config('cognito.user_pool_id'),
                'AuthParameters' => $credentials,
            ]);
        } catch (CognitoIdentityProviderException $exception) {
            return throw new CognitoException($exception);
        }
         return new User($response->toArray());
//        return $response->toArray();
    }

    /**
     * @param Authenticatable $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // TODO: Implement validateCredentials() method.
        // function decode($token) { return json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1]))));}
    }
}
