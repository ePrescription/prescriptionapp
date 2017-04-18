<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        //dd($e);
        Log::error($e->getMessage());

        //dd($e->getCode());

        /*if ($this->isHttpException($e))
        {
            //dd('Inside http');
            // Grab the HTTP status code from the Exception
            $status = $e->getCode();
            $status = 400;

            $response = [
                'errors' => 'Sorry, something went wrong.'
            ];

            return response()->json($response, $status);
        }

        if ($e instanceof \PDOException)
        {
            //dd('inside');
            //return parent::render($request, $e);
            $response = [
                'errors' => 'Unable to connect to database'
            ];

            return response()->json($response);
        }*/

        // Return a JSON response with the response array and status code
        //return response()->json($response, $status);



        return parent::render($request, $e);
    }
}
