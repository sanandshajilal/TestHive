<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Session\TokenMismatchException;

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
        // Handle CSRF Token mismatch (session expired)
        if ($exception instanceof TokenMismatchException) {
            if ($request->is('admin/*')) {
                return redirect()->route('admin.login')
                    ->with('message', 'Session expired. Please login again.');
            } else {
                return redirect()->route('home')
                    ->with('message', 'Session expired. Please login again.');
            }
        }

        // Default Laravel handling
        return parent::render($request, $exception);
    }
}
