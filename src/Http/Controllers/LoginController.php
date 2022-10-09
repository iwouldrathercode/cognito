<?php

namespace Iwouldrathercode\Cognito\Http\Controllers;

use App\Models\User as User;
use Illuminate\Http\Request;
use Iwouldrathercode\Cognito\Facades\CognitoClient;
use Iwouldrathercode\Cognito\Http\Requests\SigninRequest;
use Iwouldrathercode\Cognito\Exceptions\CognitoException;
use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;

class LoginController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | respond with authenticated user data as json output.
    |
    */
    private User $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Iwouldrathercode\Cognito\Http\Requests\SigninRequest $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Iwouldrathercode\Cognito\Exceptions\CognitoException
     */
    public function login(SigninRequest $request)
    {
        try {

            $response = CognitoClient::adminInitiateAuth([
                'AuthFlow' => config('cognito.auth_flow'),
                'ClientId' => config('cognito.client_id'),
                'UserPoolId' => config('cognito.user_pool_id'),
                'AuthParameters' => [
                    'USERNAME' => $request->username,
                    'PASSWORD' => $request->password,
                ],
            ]);

            $userResponse = CognitoClient::getUser([
                'AccessToken' => $response->get('AuthenticationResult')['AccessToken']
            ]);

            return response()->json(['data' => [
                    'user' => $this->user->where('username', $userResponse->get('Username'))->first(),
                    'cognito_data' => $response->toArray(),
                ]
            ]);

        } catch (CognitoIdentityProviderException $exception) {
            return throw new CognitoException($exception);
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        try {
            $response = CognitoClient::GlobalSignOut([
                "AccessToken" => $request->bearerToken()
            ]);
        } catch (CognitoIdentityProviderException $exception) {
            return throw new CognitoException($exception);
        }
        return response()->json(['data' => $response->toArray()], 200);
    }
}
