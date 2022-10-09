<?php

namespace Iwouldrathercode\Cognito\Exceptions;

use Exception;

class CognitoException extends Exception
{
    protected $exception;

    /**
     * Constructor
     * @param Exception $exception
     */
    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param Exception $e
     * @return void
     */
    public function report(Exception $e): void
    {
        logger()->error($e);
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request
     *
     */
    public function render($request)
    {
        $response = [
            "code" => $this->exception->getStatusCode(),
            "message" => $this->exception->getAwsErrorMessage()
        ];
        return response($response, $this->exception->getStatusCode());
    }
}
