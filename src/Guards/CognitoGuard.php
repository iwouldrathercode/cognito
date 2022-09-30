<?php

namespace Iwouldrathercode\Cognito\Guards;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Iwouldrathercode\Cognito\Exceptions\CognitoException;
use Iwouldrathercode\Cognito\Providers\CognitoUserProvider;

class CognitoGuard implements Guard
{
    use GuardHelpers;

    private CognitoUserProvider $userProvider;
    private Request $request;

    /**
     * @param CognitoUserProvider $userProvider
     * @param Request $request
     */
    public function __construct(CognitoUserProvider $userProvider, Request $request)
    {
        $this->userProvider = $userProvider;
        $this->request = $request;
    }

    /**
     * @throws CognitoException
     */
    public function user()
    {
//        if(head(Route::current()->gatherMiddleware()) === ' guest') {
//            return false;
//        }

        if(empty($this->request->bearerToken())) {
            $credentials = [
                'USERNAME' => $this->request->username,
                'PASSWORD' => $this->request->password,
            ];
            return $this->userProvider->retrieveByCredentials($credentials);
        }

        return $this->userProvider->retrieveByToken('AccessToken', $this->request->bearerToken());
    }

    /**
     * @throws CognitoException
     */
    public function validate(array $credentials = [])
    {
        $credentials = [
            'USERNAME' => $this->request->username,
            'PASSWORD' => $this->request->password,
        ];
        if($this->userProvider->retrieveByCredentials($credentials)) {
            return true;
        }
        return false;
    }
}
