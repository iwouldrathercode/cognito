<?php

namespace Iwouldrathercode\Cognito\Http\Controllers;

use App\Facades\CognitoClient;
use Iwouldrathercode\Cognito\Http\Requests\AccountConfirmationRequest;
use Iwouldrathercode\Cognito\Exceptions\CognitoException;
use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;

class VerificationController
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param \App\Http\Requests\AccountConfirmationRequest $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws CognitoException
     */
    public function verify(AccountConfirmationRequest $request)
    {
        try {
            CognitoClient::confirmSignUp([
                'ClientId' => config('cognito.client_id'),
                'Username' => $request->username,
                'ConfirmationCode' => $request->confirmation_code
            ]);

        } catch (CognitoIdentityProviderException $exception) {
            return throw new CognitoException($exception);
        }

        return response()->json([ 'data' => [
                'message' => 'User is confirmed'
            ]
        ], 201);
    }
}
