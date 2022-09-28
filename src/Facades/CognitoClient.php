<?php

namespace Iwouldrathercode\Cognito\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Iwouldrathercode\Cognito\Cognito
 */
class CognitoClient extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'CognitoClient';
    }
}
