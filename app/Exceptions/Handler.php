<?php

declare(strict_types=1);

namespace Zeropingheroes\Lanager\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

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
    #[\Override]
    public function register(): void
    {
        $this->reportable(function (Throwable $throwable): void {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    #[\Override]
    public function render($request, Throwable $e): Response
    {
        if ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException) {
            if ($request->wantsJson()) {
                return response()->json(['error' => ['message' => trans('http-status-codes.404-title')]], 404);
            }

            return Route::respondWithRoute('fallback');
        }

        return parent::render($request, $e);
    }
}
