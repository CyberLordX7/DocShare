<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
    public function register(): void
    {
        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                return $this->handleApiException($e);
            }
        });
    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $exception->errors(),
            'code' => $exception->status,
        ], $exception->status);
    }

    protected function handleApiException(Throwable $e): JsonResponse
    {
      
        if ($e instanceof ValidationException) {
            return $this->invalidJson(request(), $e);
        }

        $statusCode = $this->getStatusCode($e);
        $response = [
            'success' => false,
            'message' => $this->getErrorMessage($e),
            'code' => $statusCode,
        ];

        if (config('app.debug')) {
            $response['debug'] = [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace()
            ];
        }

        return response()->json($response, $statusCode);
    }

    protected function getStatusCode(Throwable $e): int
    {
        return match (true) {
            $e instanceof HttpException => $e->getStatusCode(),
            $e instanceof ModelNotFoundException => 404,
            $e instanceof AuthenticationException => 401,
            $e instanceof ValidationException => 422,
            default => 500,
        };
    }

    protected function getErrorMessage(Throwable $e): string
    {
        return match (true) {
            $e instanceof ModelNotFoundException => 'Resource not found',
            $e instanceof AuthenticationException => 'Unauthenticated',
            default => $e->getMessage() ?: 'Server Error',
        };
    }
}
