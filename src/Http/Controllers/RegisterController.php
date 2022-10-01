<?php

namespace Iwouldrathercode\Cognito\Http\Controllers;

use App\Models\User;
use Iwouldrathercode\Cognito\Facades\CognitoClient;
use Iwouldrathercode\Cognito\Http\Requests\SignupRequest;
use Iwouldrathercode\Cognito\Exceptions\CognitoException;
use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;

class RegisterController
{
    public function register(SignupRequest $request)
    {
        try {
            $result = CognitoClient::signUp([
                'ClientId' => config('cognito.client_id'),
                'Username' => $request->username,
                'Password' => $request->password,
                'UserAttributes' => [
                    [
                        'Name' => 'name',
                        'Value' => $request->username
                    ],
                    [
                        'Name' => 'email',
                        'Value' => $request->email
                    ]
                ],
            ]);

            $user = new User;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->sub = $result->get('UserSub');
            $user->save();

        } catch (CognitoIdentityProviderException $exception) {
            return throw new CognitoException($exception);
        }

        return response()->json(['data' => [
                'user' => $user,
                'cognito_data' => $result->toArray()
            ]
        ]);
    }
}
