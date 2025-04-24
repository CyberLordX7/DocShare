<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ApiException extends Exception
{
    public function __construct(
        string $message = "",
        int $code = 400,
        ?Throwable $previous = null 
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function render($request)
    {
        $statusCode = $this->getCode() >= 100 && $this->getCode() < 600
            ? $this->getCode()
            : 500;

        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
            'code' => $statusCode
        ], $statusCode);
    }
}
