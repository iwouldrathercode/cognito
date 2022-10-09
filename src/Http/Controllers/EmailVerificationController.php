<?php

namespace Iwouldrathercode\Cognito\Http\Controllers;

use Iwouldrathercode\Cognito\Facades\CognitoClient;
use Iwouldrathercode\Cognito\Exceptions\CognitoException;
use Iwouldrathercode\Cognito\Http\Requests\AccountConfirmationRequest;
use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;

class EmailVerificationController
{
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
