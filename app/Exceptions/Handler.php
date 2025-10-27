<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        // Define log levels for exceptions if necessary
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        // List of exceptions that shouldn't be reported (e.g., validation exceptions)
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
            // You can log exceptions here if you need to track specific ones
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $exception): \Symfony\Component\HttpFoundation\Response
    {
        // Handle 403 Forbidden (AuthorizationException)
        if ($exception instanceof AuthorizationException) {
            Log::error('Unauthorized access attempt: ' . $exception->getMessage());
            return redirect()->route('home')->with('error', 'You do not have access to this page.');
        }

        // Handle 404 Not Found
        if ($exception instanceof NotFoundHttpException) {
            return redirect()->route('home')->with('error', 'The page you requested was not found.');
        }

        // Handle other exceptions globally (e.g., ModelNotFoundException)
        if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            Log::error('Model not found: ' . $exception->getMessage());
            return redirect()->back()->with('error', 'Requested resource not found.');
        }

        // Return the parent render for unhandled exceptions
        return parent::render($request, $exception);
    }
}
