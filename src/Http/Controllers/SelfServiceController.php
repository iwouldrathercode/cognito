<?php

namespace Iwouldrathercode\Cognito\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Iwouldrathercode\Cognito\Facades\CognitoClient;
use Iwouldrathercode\Cognito\Exceptions\CognitoException;
use Iwouldrathercode\Cognito\Http\Requests\ForgotPasswordRequest;
use Iwouldrathercode\Cognito\Http\Requests\ConfirmForgotPasswordRequest;
use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;

class SelfServiceController
{
    /**
     * 
     * @param ForgotPasswordRequest $request 
     * @return JsonResponse 
     * @throws CognitoException
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        try {
            $response = CognitoClient::forgotPassword([
                'ClientId' => config('cognito.client_id'),
                'Username' => $request->username,
            ]);
        } catch (CognitoIdentityProviderException $exception) {
            return throw new CognitoException($exception);
        }

        return response()->json([ 'data' => $response->toArray()], 200);
    }

    /**
     * @param ConfirmForgotPasswordRequest $request 
     * @return JsonResponse 
     * @throws CognitoException
     */
    public function confirmForgotPassword(ConfirmForgotPasswordRequest $request)
    {
        try {
            $response = CognitoClient::confirmForgotPassword([
                'ClientId' => config('cognito.client_id'),
                'ConfirmationCode' => $request->confirmation_code,
                'Password' => $request->password,
                'Username' => $request->username
            ]);
        } catch (CognitoIdentityProviderException $exception) {
            return throw new CognitoException($exception);
        }

        return response()->json([ 'data' => $response->toArray()], 200);
    }
}
