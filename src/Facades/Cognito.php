<?php

namespace Iwouldrathercode\Cognito\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Iwouldrathercode\Cognito\Cognito
 */
class Cognito extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Iwouldrathercode\Cognito\Cognito::class;
    }
}
