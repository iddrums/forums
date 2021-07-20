<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    public function render($request, Throwable $exception)
    {
        // if (app()->environment() === 'testing') throw $exception;

        if ($exception instanceof ValidationException) {
             if ($request->expectsJson()) {
            return response()->json('Sorry, validation failed.', 422);
        }
    }

        if ($exception instanceof ThrottleException) {
            return response()->json('You are posting to frequently.', 429);
        }

        return parent::render($request, $exception);
    }
}
