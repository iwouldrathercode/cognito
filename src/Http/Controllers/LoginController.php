<?php

namespace Iwouldrathercode\Cognito\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        dd(request()->user());

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

            $request->merge([
                'access_token' => $response->get('AuthenticationResult')['AccessToken'],
                'cognito_data' => $response->toArray()
            ]);

            return $this->sendLoginResponse($request);

        } catch (CognitoIdentityProviderException $exception) {
            return throw new CognitoException($exception);
        }
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        return response()->json(['data' => [
                'user' => $request->user(),
                'cognito_data' => $request->cognito_data
            ]
        ]);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     *
     * @throws \Iwouldrathercode\Cognito\Exceptions\CognitoException
     */
    protected function authenticated(Request $request)
    {
        try {
            $response = CognitoClient::getUser([
                'AccessToken' => $request->access_token
            ]);
        } catch (CognitoIdentityProviderException $exception) {
            return throw new CognitoException($exception);
        }

        return response()->json(['data' => $response], 200);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        //
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        //
    }
}
