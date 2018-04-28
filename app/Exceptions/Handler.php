<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
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
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            return response()->error('resource not found', 404);
        }

        if ($e instanceof \App\Exceptions\ValidationException) {
            if ($e->getInput()) {
                return response()->error([
                    $e->getInput() => [
                        $e->getInvalid() => $e->getErrors()
                    ]
                ], $e->getCode());
            }

            return response()->error($e->getErrors(), $e->getCode());
        }

        if ($e instanceof \App\Exceptions\PermissionException) {
            return response()->error($e->getMessage(), 401);
        }

        return parent::render($request, $e);
    }
}
