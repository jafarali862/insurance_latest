<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Laravel\Passport\Exceptions\TokenExpiredException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


public function render($request, Throwable $exception)
{
    // Check for TokenExpiredException
    if ($exception instanceof TokenExpiredException) {
        return response()->json([
            'message' => 'Token has expired.',
            'status' => 'error'
        ], 401); // Custom status code for expired token
    }

    // Check for 403 Forbidden
    if ($exception instanceof HttpException && $exception->getStatusCode() === Response::HTTP_FORBIDDEN) {
        return response()->view('errors.403', [], 403);
    }

    

    return parent::render($request, $exception);
}

}
